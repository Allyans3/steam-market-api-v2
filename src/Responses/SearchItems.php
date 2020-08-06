<?php

namespace SteamApi\Responses;

use SteamApi\Interfaces\ResponseInterface;
use SteamApi\Mixins\Mixins;

class SearchItems implements ResponseInterface
{
    private $data;

    public function __construct($response)
    {
        $this->data = $this->decodeResponse($response);
    }

    public function response()
    {
        return $this->data;
    }

    private function decodeResponse($response)
    {
        $data = json_decode($response, true);

        if (!$data) {
            return false;
        }

        $returnData = Mixins::fillBaseData($data);

        foreach ($data['results'] as $result) {
            $returnData['items'][] = $this->completeData($result);
        }

        return $returnData;
    }

    private function completeData($data)
    {
        return [
            'name'           => $data['hash_name'],
            'image'          => "https://steamcommunity-a.akamaihd.net/economy/image/" . $data['asset_description']['icon_url'],
            'price'          => bcdiv($data['sell_price'], 100, 2),
            'currency_price' => $data['sell_price_text'],
            'volume'         => $data['sell_listings'],
            'type'           => $data['asset_description']['type'],
            'condition'      => Mixins::getCondition($data['hash_name'])
        ];
    }
}