<?php

namespace SteamApi\Responses;

use SteamApi\Interfaces\ResponseInterface;

class InspectItem implements ResponseInterface
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

    public function __destruct()
    {
        unset($this->data);
    }

    private function decodeResponse($response)
    {
        if (!is_array($response)) {
            $data = json_decode($response, true);

            if (!$data)
                return false;

            return $data;
        } else {
            $returnData = $response;

            $data = json_decode($response['response'], true);

            if (!$data) {
                $returnData['response'] = false;
                return $returnData;
            }

            $returnData['response'] = $data;

            return $returnData;
        }
    }
}