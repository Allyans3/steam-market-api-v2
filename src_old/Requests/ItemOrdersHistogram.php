<?php

namespace SteamApiOld\Requests;

use RuntimeException;
use SteamApiOld\Engine\Request;
use SteamApiOld\Interfaces\RequestInterface;

class ItemOrdersHistogram extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/market/itemordershistogram?country=%s&language=%s&currency=%s&item_nameid=%s&two_factor=0";
    const REFERER = "https://steamcommunity.com/market/listings/%s/%s";

    private $appId;
    private $market_hash_name = '';
    private $country = 'US';
    private $language = 'english';
    private $currency = 1;
    private $item_nameid = null;
    private $method = 'GET';

    public function __construct($options = [])
    {
        $this->setOptions($options);
    }

    public function getUrl(): string
    {
        return sprintf(self::URL, $this->country, $this->language, $this->currency, $this->item_nameid);
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
        if (isset($options['item_nameid']))
            $this->item_nameid = $options['item_nameid'];
        else
            throw new RuntimeException("Option 'item_nameid' must be filled");

        if (isset($options['app_id']))
            $this->app_id = $options['app_id'];
        else
            throw new RuntimeException("Option 'app_id' must be filled");

        if (isset($options['market_hash_name']))
            $this->market_hash_name = rawurlencode($options['market_hash_name']);
        else
            throw new RuntimeException("Option 'market_hash_name' must be filled");

        $this->country = isset($options['country']) ? $options['country'] : $this->country;
        $this->language = isset($options['language']) ? $options['language'] : $this->language;
        $this->currency = isset($options['currency']) ? $options['currency'] : $this->currency;
    }

    public function getHeaders(): array
    {
        return [
            'Host' => 'steamcommunity.com',
            'Origin' => 'https://steamcommunity.com/',
            'Referer' => sprintf(self::REFERER, $this->appId, $this->market_hash_name)
        ];
    }
}