<?php

namespace SteamApi\Requests;

use RuntimeException;
use SteamApi\Engine\Request;
use SteamApi\Interfaces\RequestInterface;

class ItemListingsV2 extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/market/listings/%s/%s?filter=%s";

    private $appId;
    private $filter = '';
    private $market_hash_name = '';
    private $method = 'GET';

    public function __construct($appId, $options = [])
    {
        $this->appId = $appId;
        $this->setOptions($options);
    }

    public function getUrl(): string
    {
        return sprintf(self::URL, $this->appId, $this->market_hash_name, $this->filter);
    }

    public function call($proxy = [])
    {
        return $this->steamHttpRequest($proxy);
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

        $this->filter = isset($options['filter']) ? $options['filter'] : $this->filter;
    }

    public function getHeaders() :array
    {
        return [];
    }
}