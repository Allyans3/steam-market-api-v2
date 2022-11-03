<?php

namespace SteamApi\Requests;

use RuntimeException;
use SteamApi\Engine\Request;
use SteamApi\Interfaces\RequestInterface;

class InspectItem extends Request implements RequestInterface
{
    const URL = 'https://api.csgofloat.com/?url=%s&minimal=%s';

    private $inspect_link = '';
    private $minimal = false;
    private $method = 'GET';

    public function __construct($options = [])
    {
        $this->setOptions($options);
    }

    public function getUrl(): string
    {
        return sprintf(self::URL, $this->inspect_link, $this->minimal);
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

        $this->minimal = isset($options['minimal']) ? $options['minimal'] : $this->minimal;
    }

    public function getHeaders(): array
    {
        return [];
    }
}