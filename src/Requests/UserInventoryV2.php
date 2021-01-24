<?php

namespace SteamApi\Requests;

use Psy\Exception\RuntimeException;
use SteamApi\Engine\Request;
use SteamApi\Interfaces\RequestInterface;

class UserInventoryV2 extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/profiles/%s/inventory/json/%s/%s";

    private int $appId;
    private string $steamId;
    private int $contextId = 2;
    private string $method = 'GET';

    public function __construct($appId, $options = [])
    {
        $this->appId = $appId;
        $this->setOptions($options);
    }

    public function getUrl(): string
    {
        return sprintf(self::URL, $this->steamId, $this->appId, $this->contextId);
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
    }
}