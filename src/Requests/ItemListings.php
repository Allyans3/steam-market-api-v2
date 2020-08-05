<?php

namespace SteamApi\Requests;

use Psy\Exception\RuntimeException;
use SteamApi\Engine\Request;
use SteamApi\Interfaces\RequestInterface;

class ItemListings extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/market/listings/%s/%s/render?start=%s&count=%s&currency=%s";

    private int $appId;
    private int $start = 0;
    private int $count = 100;
    private int $currency = 1;
    private string $market_hash_name = '';
    private string $method = 'GET';

    public function __construct($appId, $options = [])
    {
        $this->appId = $appId;
        $this->setOptions($options);
    }

    public function getUrl()
    {
        return sprintf(self::URL, $this->appId, $this->market_hash_name, $this->start, $this->count, $this->currency);
    }

    public function call($options = [])
    {
        return $this->setOptions($options)->steamHttpRequest();
    }

    public function getRequestMethod()
    {
        return $this->method;
    }

    private function setOptions($options)
    {
        if (isset($options['market_hash_name'])) {
            $this->market_hash_name = rawurlencode($options['market_hash_name']);
        } else {
            throw new RuntimeException("Option 'market_hash_name' must be filled");
        }

        $this->start = isset($options['start']) ? $options['start'] : $this->start;
        $this->count = isset($options['count']) ? $options['count'] : $this->count;
        $this->currency = isset($options['currency']) ? $options['currency'] : $this->currency;

        return $this;
    }
}