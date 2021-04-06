<?php

namespace SteamApi\Requests;

use Psy\Exception\RuntimeException;
use SteamApi\Engine\Request;
use SteamApi\Interfaces\RequestInterface;

class ItemListings extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/market/listings/%s/%s/render?start=%s&count=%s&currency=%s&language=%s&filter=%s";

    private $appId;
    private $start = 0;
    private $count = 100;
    private $currency = 1;
    private $filter = '';
    private $language = 'english';
    private $market_hash_name = '';
    private $method = 'GET';

    public function __construct($appId, $options = [])
    {
        $this->appId = $appId;
        $this->setOptions($options);
    }

    public function getUrl(): string
    {
        return sprintf(self::URL, $this->appId, $this->market_hash_name, $this->start, $this->count,
                        $this->currency, $this->language, $this->filter);
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

        $this->start = isset($options['start']) ? $options['start'] : $this->start;
        $this->count = isset($options['count']) ? $options['count'] : $this->count;
        $this->currency = isset($options['currency']) ? $options['currency'] : $this->currency;
        $this->filter = isset($options['filter']) ? $options['filter'] : $this->filter;
    }
}