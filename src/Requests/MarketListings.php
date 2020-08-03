<?php

namespace SteamApi\Requests;

use SteamApi\Engine\Request;
use SteamApi\Interfaces\RequestInterface;

class MarketListings extends Request implements RequestInterface
{
    const URL = 'https://steamcommunity.com/market/search/render/?start=%s&count=%s&sort_column=%s&sort_dir=%s&appid=%s&norender=1';

    private int $appId;
    private int $start = 0;
    private int $count = 100;
    private string $sort_column = 'price';
    private string $sort_dir = 'asc';
    private string $method = 'GET';

    public function __construct($appId, $options = [])
    {
        $this->appId = $appId;
        $this->setOptions($options);
    }

    public function getUrl()
    {
        return sprintf(self::URL, $this->start, $this->count, $this->sort_column, $this->sort_dir, $this->appId);
    }

    public function call($options = [])
    {
        return $this->setOptions($options)->steamHttpRequest();
    }

    public function getRequestMethod()
    {
        return $this->method;
    }

    private function setOptions($options)
    {
        $this->start = isset($options['start']) ? $options['start'] : $this->start;
        $this->count = isset($options['count']) ? $options['count'] : $this->count;
        $this->sort_column = isset($options['sort_column']) ? $options['sort_column'] : $this->sort_column;
        $this->sort_dir = isset($options['sort_dir']) ? $options['sort_dir'] : $this->sort_dir;

        return $this;
    }
}