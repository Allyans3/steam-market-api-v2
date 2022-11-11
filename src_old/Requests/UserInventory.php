<?php

namespace SteamApiOld\Requests;

use RuntimeException;
use SteamApiOld\Engine\Request;
use SteamApiOld\Interfaces\RequestInterface;

class UserInventory extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/inventory/%s/%s/%s?l=%s&count=%s&start_assetid=%s";

    private $appId;
    private $steamId;
    private $contextId = 2;
    private $language = 'english';
    private $count = 75;
    private $startAssetId = '';
    private $method = 'GET';

    public function __construct($appId, $options = [])
    {
        $this->appId = $appId;
        $this->setOptions($options);
    }

    public function getUrl(): string
    {
        return sprintf(self::URL, $this->steamId, $this->appId, $this->contextId, $this->language, $this->count, $this->startAssetId);
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
        if (isset($options['steamId']))
            $this->steamId = rawurlencode($options['steamId']);
        else
            throw new RuntimeException("Option 'steamId' must be filled");

        $this->contextId = isset($options['contextId']) ? $options['contextId'] : $this->contextId;
        $this->language = isset($options['language']) ? $options['language'] : $this->language;
        $this->count = isset($options['count']) ? $options['count'] : $this->count;
        $this->startAssetId = isset($options['startAssetId']) ? $options['startAssetId'] : $this->startAssetId;
    }

    public function getHeaders(): array
    {
        return [];
    }
}