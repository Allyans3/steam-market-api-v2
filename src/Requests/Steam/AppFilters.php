<?php

namespace SteamApi\Requests\Steam;

use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Interfaces\RequestInterface;

class AppFilters extends Request implements RequestInterface
{
    const REFERER = "https://steamcommunity.com/market/";
    const URL = "https://steamcommunity.com/market/appfilters/%s";

    private $method = 'GET';

    private $appId;

    /**
     * @param $appId
     */
    public function __construct($appId)
    {
        $this->appId = $appId;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return sprintf(self::URL, $this->appId);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Host' => 'steamcommunity.com',
            'Origin' => 'https://steamcommunity.com/',
            'Referer' => self::REFERER
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
}