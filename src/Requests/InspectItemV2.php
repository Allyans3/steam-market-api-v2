<?php

namespace SteamApi\Requests;

use RuntimeException;
use SteamApi\Engine\Request;
use SteamApi\Interfaces\RequestInterface;

class InspectItemV2 extends Request implements RequestInterface
{
    const URL = 'https://floats.gainskins.com/?url=%s';

    private $inspect_link = '';
    private $method = 'GET';

    public function __construct($options = [])
    {
        $this->setOptions($options);
    }

    public function getUrl(): string
    {
        return sprintf(self::URL, $this->inspect_link);
    }

    public function call($proxy = [], $detailed = false)
    {
        return $this->steamHttpRequest($proxy, $detailed);
    }

    public function getRequestMethod(): string
    {
        return $this->method;
    }

    private function setOptions($options)
    {
        if (isset($options['inspect_link']))
            $this->inspect_link = $options['inspect_link'];
        else
            throw new RuntimeException("Option 'inspect_link' must be filled");
    }

    public function getHeaders(): array
    {
        return [];
    }
}