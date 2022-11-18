<?php

namespace SteamApi\Requests\Steam;

use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Exception\InvalidOptionsException;
use SteamApi\Interfaces\RequestInterface;

class UserInventory extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/inventory/%s/%s/%s?l=%s&count=%s&start_assetid=%s";

    private $method = 'GET';

    private $appId;
    private $steamId;
    private $contextId = 2;

    private $language = 'english';
    private $count = 75;
    private $startAssetId = '';

    /**
     * @param $appId
     * @param array $options
     * @throws InvalidOptionsException
     */
    public function __construct($appId, array $options = [])
    {
        $this->appId = $appId;
        $this->setOptions($options);
    }

    public function getUrl(): string
    {
        return sprintf(self::URL, $this->steamId, $this->appId, $this->contextId, $this->language,
            $this->count, $this->startAssetId);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Host' => 'steamcommunity.com',
            'Origin' => 'https://steamcommunity.com/',
            'Referer' => "https://steamcommunity.com/profiles/{$this->steamId}/inventory/"
        ];
    }

    /**
     * @param array $proxy
     * @param false $detailed
     * @param false $multiRequest
     * @param array $curlOpts
     * @return mixed|void
     * @throws InvalidClassException
     */
    public function call(array $proxy = [], bool $detailed = false, bool $multiRequest = false, array $curlOpts = [])
    {
        return $this->makeRequest($proxy, $detailed, $multiRequest, $curlOpts);
    }

    /**
     * @return string
     */
    public function getRequestMethod(): string
    {
        return $this->method;
    }

    /**
     * @param $options
     * @throws InvalidOptionsException
     */
    private function setOptions($options)
    {
        if (isset($options['steam_id']))
            $this->steamId = $options['steam_id'];
        else
            throw new InvalidOptionsException("Option 'steam_id' must be filled");

        $this->contextId = isset($options['context_id']) ? $options['context_id'] : $this->contextId;
        $this->language = isset($options['language']) ? $options['language'] : $this->language;
        $this->count = isset($options['count']) ? $options['count'] : $this->count;
        $this->startAssetId = isset($options['start_asset_id']) ? $options['start_asset_id'] : $this->startAssetId;
    }
}