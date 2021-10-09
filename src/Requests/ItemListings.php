<?php

namespace SteamApi\Requests;

use Psy\Exception\RuntimeException;
use SteamApi\Engine\Request;
use SteamApi\Interfaces\RequestInterface;

class ItemListings extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/market/listings/%s/%s/render?query=%s&start=%s&count=%s&currency=%s&country=%s&language=%s&filter=%s";

    private $appId;
    private $query = '';
    private $start = 0;
    private $count = 100;
    private $currency = 1;
    private $country = 'EN';
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
                        $this->currency, $this->country, $this->language, $this->filter);
    }

    public function call($proxy = [], $detailed = false, $multi = false)
    {
        if ($multi)
            return $this->steamMultiHttpRequest($proxy);
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