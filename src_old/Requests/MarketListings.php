<?php

namespace SteamApiOld\Requests;

use SteamApiOld\Engine\Request;
use SteamApiOld\Interfaces\RequestInterface;

class MarketListings extends Request implements RequestInterface
{
    const URL = 'https://steamcommunity.com/market/search/render/?start=%s&count=%s&sort_column=%s&sort_dir=%s&appid=%s&norender=1';

    private $appId;
    private $start = 0;
    private $count = 100;
    private $sort_column = null;
    private $sort_dir = null;
    private $method = 'GET';

    public function __construct($appId, $options = [])
    {
        $this->appId = $appId;
        $this->setOptions($options);
    }

    public function getUrl(): string
    {
        return sprintf(self::URL, $this->start, $this->count, $this->sort_column, $this->sort_dir, $this->appId);
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
        $this->start = isset($options['start']) ? $options['start'] : $this->start;
        $this->count = isset($options['count']) ? $options['count'] : $this->count;
        $this->sort_column = isset($options['sort_column']) ? $options['sort_column'] : $this->sort_column;
        $this->sort_dir = isset($options['sort_dir']) ? $options['sort_dir'] : $this->sort_dir;
    }

    public function getHeaders(): array
    {
        return [];
    }
}