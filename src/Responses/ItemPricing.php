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
        if (array_key_exists('lowest_price', $data)) {
            $data['lowest_price_text'] = $data['lowest_price'];
            $data['lowest_price'] = Mixins::toFloat($data['lowest_price']);
        }

        if (array_key_exists('median_price', $data)) {
            $data['median_price_text'] = $data['median_price'];
            $data['median_price'] = Mixins::toFloat($data['median_price']);
        }

        return $data;
    }
}