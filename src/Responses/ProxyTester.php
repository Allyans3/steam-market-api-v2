<?php

namespace SteamApi\Responses;

use SteamApi\Interfaces\ResponseInterface;

class ProxyTester implements ResponseInterface
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
        return json_decode($response, true);
    }
}