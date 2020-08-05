<?php

namespace SteamApi\Requests;

use SteamApi\Engine\Request;
use SteamApi\Interfaces\RequestInterface;

class ItemOrdersHistogram extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/market/itemordershistogram?country=%s&language=%s&currency=%s&item_nameid=%s&two_factor=0";

    private string $country = 'US';
    private string $language = 'english';
    private int $currency = 1;
    private ?int $item_nameid = null;
    private string $method = 'GET';

    public function __construct($options = [])
    {
        $this->setOptions($options);
    }

    public function getUrl()
    {
        return sprintf(self::URL, $this->country, $this->language, $this->currency, $this->item_nameid);
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
        $this->country = isset($options['country']) ? $options['country'] : $this->country;
        $this->language = isset($options['language']) ? $options['language'] : $this->language;
        $this->currency = isset($options['currency']) ? $options['currency'] : $this->currency;
        $this->item_nameid = isset($options['item_nameid']) ? $options['item_nameid'] : $this->item_nameid;

        return $this;
    }
}