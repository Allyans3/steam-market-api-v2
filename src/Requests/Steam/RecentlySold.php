<?php

namespace SteamApi\Requests\Steam;

use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Interfaces\RequestInterface;

class RecentlySold extends Request implements RequestInterface
{
    const REFERER = "https://steamcommunity.com/market/";
    const URL = "https://steamcommunity.com/market/recentcompleted?norender=1";

    private $method = 'GET';

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return self::URL;
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