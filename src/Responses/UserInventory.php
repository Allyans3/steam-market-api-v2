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
        $multi_curl = new MultiCurl();
        $multi_curl->setConcurrency(100);

        $data = json_decode($response, true);

        if (is_null($data) || array_key_exists('error', $data)) {
            return false;
        }

        $returnData = [];

        foreach ($data['assets'] as $asset) {
            foreach ($data['descriptions'] as &$description) {
                if ($asset['classid'] === $description['classid']) {

                    $inspectLink = Mixins::createSteamLink($asset, $description);
                    $description['inspectLink'] = $inspectLink;

                    if ($inspectLink)
                        $multi_curl->addGet('https://api.csgofloat.com/', array(
                            'url' => $inspectLink
                        ));
                    else
                        $returnData[] = $this->completeData($asset, $description, []);

                    break;
                }
            }
        }

        $multi_curl->success(function($instance) use(&$returnData, $data) {

            $parts = parse_url($instance->url);
            parse_str($parts['query'], $query);

            $itemInfo = json_decode(json_encode($instance->response->iteminfo), true);

            foreach ($data['assets'] as $asset) {
                foreach ($data['descriptions'] as $description) {
                    if ($asset['classid'] === $description['classid'] &&
                        $description['inspectLink'] === $query['url'])
                    {

                        $returnData[] = $this->completeData($asset, $description, $itemInfo);
                        break;
                    }
                }
            }
        });

        $multi_curl->start();

        return $returnData;
    }

    private function completeData($asset, $description, $inspectItem)
    {
        $steamImgUrl = "https://steamcommunity-a.akamaihd.net/economy/image/";
        $cloudFlareUmgUrl = "https://community.cloudflare.steamstatic.com/economy/image/";

        $baseInfo =  [
            'assetid'         => $asset['assetid'],
            'classid'         => $asset['classid'],
            'instanceid'      => $asset['instanceid'],
            'amount'          => $asset['amount'],

            'name'            => $description['market_hash_name'],
            'type'            => $description['type'],
            'image'           => $steamImgUrl . $description['icon_url'],
            'imageLarge'      => isset($description['icon_url_large']) ? $steamImgUrl . $description['icon_url_large'] : null,
            'image_cf'        => $cloudFlareUmgUrl . $description['icon_url'],
            'imageLarge_cf'   => isset($description['icon_url_large']) ? $cloudFlareUmgUrl . $description['icon_url_large'] : null,
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