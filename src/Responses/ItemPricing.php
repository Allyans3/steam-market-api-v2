<?php

namespace SteamApi\Responses;

use SteamApi\Interfaces\ResponseInterface;
use SteamApi\Mixins\Mixins;

class ItemPricing implements ResponseInterface
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

        return $this->completeData($data);
    }

    private function completeData($data)
    {
        return [
            'success' => $data['success'],
            'volume' => $data['volume'],

            'lowest_price' => Mixins::toFloat($data['lowest_price']),
            'lowest_price_str' => $data['lowest_price'],

            'median_price' => Mixins::toFloat($data['median_price']),
            'median_price_str' => $data['median_price']
        ];
    }
}