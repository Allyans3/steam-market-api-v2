<?php

namespace SteamApi\Requests;

use SteamApi\Engine\Request;
use SteamApi\Interfaces\RequestInterface;

class NewlyListed extends Request implements RequestInterface
{
    const URL = 'https://steamcommunity.com/market/recent?country=%s&language=%s&currency=%s&norender=1';

    private $country = 'US';
    private $language = 'english';
    private $currency = 1;
    private $method = 'GET';

    public function __construct($options = [])
    {
        $this->setOptions($options);
    }

    public function getUrl(): string
    {
        return sprintf(self::URL, $this->country, $this->language, $this->currency);
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
        $this->country = isset($options['country']) ? $options['country'] : $this->country;
        $this->language = isset($options['language']) ? $options['language'] : $this->language;
        $this->currency = isset($options['currency']) ? $options['currency'] : $this->currency;
    }
}