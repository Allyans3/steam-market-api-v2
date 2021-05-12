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
        if (!is_array($response)) {
            $data = json_decode($response, true);

            if (!$data)
                return false;

            return $this->completeData($data);
        } else {
            $returnData = $response;

            $data = json_decode($response['response'], true);

            if (!$data) {
                $returnData['response'] = false;
                return $returnData;
            }

            $returnData['response'] = $this->completeData($data);

            return $returnData;
        }
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