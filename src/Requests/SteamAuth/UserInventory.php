<?php

namespace SteamApi\Requests\SteamAuth;

use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Exception\InvalidOptionsException;
use SteamApi\Interfaces\RequestInterface;

class UserInventory extends Request implements RequestInterface
{
    const REFERER = "https://steamcommunity.com/profiles/%s/inventory/";
    const URL = "https://steamcommunity.com/profiles/%s/inventory/json/%s/%s?l=%s";

    private $method = 'GET';

    private $appId;
    private $steamId;
    private $contextId = 2;

    private $language = 'english';

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

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return sprintf(self::URL, $this->steamId, $this->appId, $this->contextId, $this->language);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Host' => 'steamcommunity.com',
            'Origin' => 'https://steamcommunity.com/',
            'Referer' => sprintf(self::REFERER, $this->steamId)
        ];
    }

    /**
     * @param array $proxy
     * @param false $detailed
     * @param false $multiRequest
     * @param string $cookies
     * @param array $curlOpts
     * @return mixed|void
     * @throws InvalidClassException
     */
    public function call(array $proxy = [], string $cookies = '', bool $detailed = false, array $curlOpts = [], bool $multiRequest = false)
    {
        return $this->makeRequest($proxy, $cookies, $detailed, $curlOpts, $multiRequest);
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
    }
}