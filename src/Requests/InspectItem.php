<?php

namespace SteamApi\Requests;

use SteamApi\Engine\Request;
use SteamApi\Interfaces\RequestInterface;

class InspectItem extends Request implements RequestInterface
{
    const URL = 'https://api.csgofloat.com/?url=%s';

    private string $inspectLink = '';
    private string $method = 'GET';

    public function __construct($inspectLink)
    {
        $this->inspectLink = $inspectLink;
    }

    public function getUrl(): string
    {
        return sprintf(self::URL, $this->inspectLink);
    }

    public function call()
    {
        return $this->steamHttpRequest();
    }

    public function getRequestMethod(): string
    {
        return $this->method;
    }
}