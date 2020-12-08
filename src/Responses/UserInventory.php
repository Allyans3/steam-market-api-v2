<?php

namespace SteamApi\Responses;

use Curl\MultiCurl;
use SteamApi\Interfaces\ResponseInterface;
use SteamApi\Mixins\Mixins;
use SteamApi\SteamApi;

class UserInventory implements ResponseInterface
{
    private $steamApi;
    private $data;

    public function __construct($response)
    {
        $this->steamApi = new SteamApi();
        $this->data = $this->decodeResponse($response);
    }

    public function response()
    {
        return $this->data;
    }

    private function decodeResponse($response)
    {
        $data = json_decode($response, true);

        if (is_null($data) || array_key_exists('error', $data)) {
            return false;
        }

        $returnData = [];

        foreach ($data['assets'] as $asset) {
            foreach ($data['descriptions'] as $description) {
                if ($asset['classid'] === $description['classid']) {

                    $inspectLink = Mixins::createSteamLink($asset, $description);
                    $inspectItem = [];

                    if ($inspectLink)
                        $inspectItem = $this->steamApi->inspectItem($inspectLink);

                    $returnData[] = $this->completeData($asset, $description, $inspectItem);
                    break;
                }
            }
        }

        return $returnData;
    }

    private function completeData($asset, $description, $inspectItem)
    {
        $baseInfo =  [
            'assetid'         => $asset['assetid'],
            'classid'         => $asset['classid'],
            'instanceid'      => $asset['instanceid'],
            'amount'          => $asset['amount'],

            'name'            => $description['market_hash_name'],
            'type'            => $description['type'],
            'image'           => "https://steamcommunity-a.akamaihd.net/economy/image/" . $description['icon_url'],
            'withdrawable_at' => $description['market_tradable_restriction'],
            'marketable'      => $description['marketable'],
            'tradable'        => $description['tradable'],
        ];

        $addInfo = [];

        if (isset($description['fraudwarnings'])) {
            $baseInfo['nameTag'] = Mixins::parseNameTag($description['fraudwarnings'][0]);
        }

        if (array_key_exists('iteminfo', $inspectItem)) {
            $addInfo = [
                'condition'  => $inspectItem['iteminfo']['wear_name'],
                'float'      => $inspectItem['iteminfo']['floatvalue'],
                'paintseed'  => $inspectItem['iteminfo']['paintseed'],
                'paintindex' => $inspectItem['iteminfo']['paintindex'],
                'stickers'   => $inspectItem['iteminfo']['stickers']
            ];
        }

        return array_merge($baseInfo, $addInfo);
    }
}