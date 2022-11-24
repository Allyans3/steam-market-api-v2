<?php

namespace SteamApi\Requests\Steam;

use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Interfaces\RequestInterface;

class SearchItems extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/market/search/render?query=%s&appid=%s&start=%s&count=%s&search_descriptions=%s%s&norender=1";

    private $method = 'GET';

    private $appId;

    private $query = '';
    private $start = 0;
    private $count = 10;
    private $searchDescriptions = 0;
    private $exact = false;
    private $filter = '';

    /**
     * @param $appId
     * @param array $options
     */
    public function __construct($appId, array $options = [])
    {
        $this->appId = $appId;
        $this->setOptions($options);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return sprintf(self::URL, $this->query, $this->appId, $this->start, $this->count,
            $this->searchDescriptions, $this->filter);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Host' => 'steamcommunity.com',
            'Origin' => 'https://steamcommunity.com/',
            'Referer' => 'https://steamcommunity.com/market/search'
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

    /**
     * @param $options
     */
    private function setOptions($options)
    {
        $this->start = isset($options['start']) ? $options['start'] : $this->start;
        $this->count = isset($options['count']) ? $options['count'] : $this->count;
        $this->exact = isset($options['exact']) ? $options['exact'] : $this->exact;
        $this->searchDescriptions = isset($options['search_descriptions']) ? $options['search_descriptions'] : $this->searchDescriptions;

        $this->query = isset($options['query']) ?
            ($this->exact ? sprintf('"%s"', rawurlencode($options['query'])) : rawurlencode($options['query'])) :
            $this->query;

        $this->filter = isset($options['filter']) ? '&' . http_build_query($options['filter']) : $this->filter;
    }
}