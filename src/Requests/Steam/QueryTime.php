<?php

namespace SteamApi\Requests\Steam;

use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Interfaces\RequestInterface;

class QueryTime extends Request implements RequestInterface
{
    const URL = "https://api.steampowered.com/ITwoFactorService/QueryTime/v1";

    private $method = 'POST';

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
        return [];
    }

    /**
     * @return array
     */
    public function getFormData(): array
    {
        return [];
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
}