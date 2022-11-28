<?php

namespace SteamApi\Requests\SteamAuth;

use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Interfaces\RequestInterface;

class MyListings extends Request implements RequestInterface
{
    const REFERER = "https://steamcommunity.com/market";
    const URL = "https://steamcommunity.com/market/mylistings/render/?query=%s&start=%s&count=%s&norender=1";

    private $method = 'GET';

    private $query = '';
    private $start = 0;
    private $count = 30;

    /**
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->setOptions($options);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return sprintf(self::URL, $this->query, $this->start, $this->count);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Host' => 'steamcommunity.com',
            'Origin' => 'https://steamcommunity.com/',
            'Referer' => self::REFERER
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

    /**
     * @param array $options
     */
    private function setOptions(array $options)
    {
        $this->query = isset($options['query']) ? $options['query'] : $this->query;
        $this->start = isset($options['start']) ? $options['start'] : $this->start;
        $this->count = isset($options['count']) ? $options['count'] : $this->count;
    }
}