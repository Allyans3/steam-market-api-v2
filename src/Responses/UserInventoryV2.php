<?php

namespace SteamApi\Responses;

use SteamApi\Interfaces\ResponseInterface;
use SteamApi\Mixins\Mixins;
use SteamApi\SteamApi;

class UserInventoryV2 implements ResponseInterface
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

        foreach ($data['rgInventory'] as $asset) {

            $description = $data['rgDescriptions'][$asset['classid'] . '_' . $asset['instanceid']];
            $inspectLink = Mixins::createSteamLink($asset['id'], $description);

            $inspectItem = [];

            if ($inspectLink)
                $inspectItem = $this->steamApi->inspectItem($inspectLink);

            $returnData[] = $this->completeData($asset, $description, $inspectItem);
        }

        return $returnData;
    }

    private function completeData($asset, $description, $inspectItem): array
    {
        $baseInfo =  [
            'assetid'         => $asset['id'],
            'classid'         => $asset['classid'],
            'instanceid'      => $asset['instanceid'],
            'amount'          => $asset['amount'],

            'name'            => $description['market_hash_name'],
            'type'            => $description['type'],
            'image'           => "https://steamcommunity-a.akamaihd.net/economy/image/" . $description['icon_url'],
            'imageLarge'      => isset($description['icon_url_large']) ? "https://steamcommunity-a.akamaihd.net/economy/image/" . $description['icon_url_large'] : '',
            'image_cf'        => "https://community.cloudflare.steamstatic.com/economy/image/" . $description['icon_url'],
            'imageLarge_cf'   => isset($description['icon_url_large']) ? "https://community.cloudflare.steamstatic.com/economy/image/" . $description['icon_url_large'] : '',
            'withdrawable_at' => $description['market_tradable_restriction'],
            'marketable'      => $description['marketable'],
            'tradable'        => $description['tradable'],
        ];

        $addInfo = [];

        if (isset($description['fraudwarnings'])) {
            $baseInfo['nameTag'] = Mixins::parseNameTag($description['fraudwarnings'][0]);
        }

        if ($inspectItem && array_key_exists('iteminfo', $inspectItem)) {
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