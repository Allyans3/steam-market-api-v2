<?php

namespace SteamApiOld\Requests;

use RuntimeException;
use SteamApiOld\Engine\Request;
use SteamApiOld\Interfaces\RequestInterface;

class ItemListings extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/market/listings/%s/%s/render/?query=%s&start=%s&count=%s&country=%s&language=%s&currency=%s&filter=%s";
    const REFERER = "https://steamcommunity.com/market/listings/%s/%s";

    private $appId;
    private $query = '';
    private $start = 0;
    private $count = 100;
    private $currency = 1;
    private $country = 'US';
    private $language = 'english';
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
        return sprintf(self::URL, $this->appId, $this->market_hash_name, $this->query, $this->start, $this->count,
                        $this->country, $this->language, $this->currency, $this->filter);
    }

    public function getHeaders(): array
    {
        return [
            'Host' => 'steamcommunity.com',
            'Origin' => 'https://steamcommunity.com/',
            'Referer' => sprintf(self::REFERER, $this->appId, $this->market_hash_name) . ($this->filter ? '?filter=' . $this->filter : '')
        ];
    }

    public function call($proxy = [], $detailed = false, $multi = false, $smartMulti = false)
    {
        if ($multi)
            return $this->steamMultiHttpRequest($proxy, $detailed, $smartMulti);
        else
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

        $this->query = isset($options['query']) ? $options['query'] : $this->query;
        $this->start = isset($options['start']) ? $options['start'] : $this->start;
        $this->count = isset($options['count']) ? $options['count'] : $this->count;
        $this->currency = isset($options['currency']) ? $options['currency'] : $this->currency;
        $this->country = isset($options['country']) ? $options['country'] : $this->country;
        $this->language = isset($options['language']) ? $options['language'] : $this->language;
        $this->filter = isset($options['filter']) ? $options['filter'] : $this->filter;
    }
}