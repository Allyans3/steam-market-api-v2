<?php

namespace SteamApi\Requests;

use Psy\Exception\RuntimeException;
use SteamApi\Engine\Request;
use SteamApi\Interfaces\RequestInterface;

class UserInventory extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/inventory/%s/%s/%s?l=%s&count=%s&start_assetid=%s";

    private int $appId;
    private string $steamId;
    private int $contextId = 2;
    private string $language = 'english';
    private int $count = 75;
    private string $startAssetId = '';
    private string $method = 'GET';

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
}