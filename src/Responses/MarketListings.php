<?php

namespace SteamApi\Responses;

use SteamApi\Config\Config;
use SteamApi\Interfaces\ResponseInterface;
use SteamApi\Mixins\Mixins;

class MarketListings implements ResponseInterface
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
        if (!is_array($response)) {
            $data = json_decode($response, true);

            if (!$data)
                return false;

            $returnData = Mixins::fillBaseData($data);

            foreach ($data['results'] as $result) {
                $returnData['items'][] = $this->completeData($result);
            }

            return $returnData;
        } else {
            $returnData = $response;

            $data = json_decode($response['response'], true);

            if (!$data) {
                $returnData['response'] = false;
                return $returnData;
            }

            $returnData['response'] = Mixins::fillBaseData($data);

            foreach ($data['results'] as $result) {
                $returnData['response']['items'][] = $this->completeData($result);
            }

            return $returnData;
        }
    }

    private function completeData($data): array
    {
        return [
            'name'          => $data['hash_name'],
            'image'         => "https://steamcommunity-a.akamaihd.net/economy/image/" . $data['asset_description']['icon_url'],
            'currency'      => Config::CURRENCY[$data['asset_description']['currency']],
            'price'         => Mixins::toFloat($data['sell_price_text']),
            'price_text'    => $data['sell_price_text'],
            'sell_listings' => $data['sell_listings'],
            'type'          => $data['asset_description']['type'],
            'condition'     => Mixins::getCondition($data['hash_name'])
        ];
    }
}