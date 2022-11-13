<?php

namespace SteamApiOld\Requests;

use SteamApiOld\Engine\Request;
use SteamApiOld\Interfaces\RequestInterface;

class ProxyTester extends Request implements RequestInterface
{
    const URL = 'http://httpbin.org/ip';

    private $method = 'GET';

    public function getUrl(): string
    {
        return self::URL;
    }

    public function call($proxy = [])
    {
        return $this->steamHttpRequest($proxy);
    }

    public function getRequestMethod(): string
    {
        return $this->method;
    }

    public function getHeaders(): array
    {
        return [];
    }
}