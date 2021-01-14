<?php

namespace SteamApi\Requests;

use SteamApi\Engine\Request;
use SteamApi\Interfaces\RequestInterface;

class SearchItems extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/market/search/render?appid=%s&start=%s&count=%s&query=%s&norender=1";

    private ?int $appId = null;
    private int $start = 0;
    private int $count = 100;
    private string $query = '';
    private bool $exact = false;
    private string $method = 'GET';

    public function __construct($appId, $options = [])
    {
        $this->appId = $appId;
        $this->setOptions($options);
    }

    public function getUrl(): string
    {
        return sprintf(self::URL, $this->appId, $this->start, $this->count, $this->query);
    }

    public function call($proxy = [])
    {
        return $this->steamHttpRequest($proxy);
    }

    public function getRequestMethod(): string
    {
        return $this->method;
    }

    private function setOptions($options)
    {
        $this->start = isset($options['start']) ? $options['start'] : $this->start;
        $this->count = isset($options['count']) ? $options['count'] : $this->count;
        $this->exact = isset($options['exact']) ? $options['exact'] : $this->exact;

        $this->query = isset($options['query']) ?
            ($this->exact ? sprintf('"%s"', rawurlencode($options['query'])) : rawurlencode($options['query'])) :
            $this->query;
    }
}