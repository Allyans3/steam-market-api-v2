<?php

namespace SteamApiOld\Requests;

use RuntimeException;
use SteamApiOld\Engine\Request;
use SteamApiOld\Interfaces\RequestInterface;

class SaleHistory extends Request implements RequestInterface
{
    const URL = 'https://steamcommunity.com/market/listings/%s/%s';

    private $appId;
    private $market_hash_name = '';
    private $method = 'GET';

    public function __construct($appId, $options = [])
    {
        $this->appId = $appId;
        $this->setOptions($options);
    }

    public function getUrl(): string
    {
        return sprintf(self::URL, $this->appId, $this->market_hash_name);
    }

    public function call($proxy = [], $detailed = false)
    {
        return $this->steamHttpRequest($proxy, $detailed);
    }

    public function getRequestMethod(): string
    {
        return $this->method;
    }

    private function setOptions($options)
    {
        if (isset($options['market_hash_name']))
            $this->market_hash_name = rawurlencode($options['market_hash_name']);
        else
            throw new RuntimeException("Option 'market_hash_name' must be filled");
    }

    public function getHeaders(): array
    {
        return [];
    }
}