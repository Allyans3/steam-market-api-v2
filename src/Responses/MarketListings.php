<?php

namespace SteamApi\Responses;

use SteamApi\Engine\XmlStringStreamerAbstract;
use SteamApi\Interfaces\ResponseInterface;
use const SteamApi\Config\CURRENCY;

class MarketListings extends XmlStringStreamerAbstract implements ResponseInterface
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
        $data = json_decode($response);

        if (!$data) {
            return false;
        }

        $listings = [];

        foreach ($data['results'] as $result) {
            $listings[] = $this->completeData($result);
        }

        return $listings;
    }

    private function completeData($data)
    {
        return [
            'name'       => $data['hash_name'],
            'image'      => "https://steamcommunity-a.akamaihd.net/economy/image/" . $data['asset_description']['icon_url'],
            'curr_price' => $data['sell_price_text'],
            'currency'   => CURRENCY[$data['asset_description']['currency']],
            'price'      => bcdiv($data['sell_price'], 100, 2),
            'volume'     => $data['sell_listings'],
            'type'       => $data['asset_description']['type']
        ];
    }
}