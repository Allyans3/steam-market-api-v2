<?php

namespace SteamApi\Requests\Inspectors;

use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Interfaces\RequestInterface;

class InspectItem extends Request implements RequestInterface
{
    const URL = 'https://api.csgofloat.com/?url=%s';

    private $method = 'GET';

    private $inspectLink;

    /**
     * @param string $inspectLink
     */
    public function __construct(string $inspectLink)
    {
        $this->inspectLink = rawurlencode($inspectLink);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return sprintf(self::URL, $this->inspectLink);
    }

    /**
     * @return string[]
     */
    public function getHeaders(): array
    {
        return [
            'Origin' => 'chrome-extension://jjicbefpemnphinccgikpdaagjebbnhg'
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
}