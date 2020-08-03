<?php

namespace SteamApi\Responses;

use SteamApi\Interfaces\ResponseInterface;

class ItemPricing implements ResponseInterface
{
    private $raw;
    private array $data;

    public function __construct($response)
    {
        $this->raw = $response;
        $this->data = $this->decodeResponse($response);
    }

    public function response()
    {
        return $this->data;
    }

    public function raw()
    {
        return $this->raw;
    }

    private function decodeResponse($response)
    {
        return json_decode($response, true);
    }
}