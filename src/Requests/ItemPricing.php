<?php

namespace SteamApi\Requests;

use Psy\Exception\RuntimeException;
use SteamApi\Engine\Request;
use SteamApi\Interfaces\RequestInterface;

class ItemPricing extends Request implements RequestInterface
{
    const URL = 'https://steamcommunity.com/market/priceoverview/?appid=%s&currency=%s&market_hash_name=%s';

    private int $appId;
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
        return sprintf(self::URL, $this->appId, $this->currency, $this->market_hash_name);
    }

    public function call($options = [], $proxy = [])
    {
        return $this->setOptions($options)->steamHttpRequest($proxy);
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

        $this->currency = isset($options['currency']) ? $options['currency'] : $this->currency;

        return $this;
    }
}