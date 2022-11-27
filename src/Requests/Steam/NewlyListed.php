<?php

namespace SteamApi\Requests\Steam;

use Carbon\Carbon;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Engine\Request;
use SteamApi\Interfaces\RequestInterface;

class NewlyListed extends Request implements RequestInterface
{
    const REFERER = "https://steamcommunity.com/market/";
    const URL = "https://steamcommunity.com/market/recent?country=%s&language=%s&currency=%s&norender=1";

    private $method = 'GET';

    private $country = 'US';
    private $language = 'english';
    private $currency = 1;

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
        return sprintf(self::URL, $this->country, $this->language, $this->currency);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Host' => 'steamcommunity.com',
            'Origin' => 'https://steamcommunity.com/',
            'If-Modified-Since' => Carbon::now('UTC')->subSeconds(10)->toRfc7231String(),
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
        $this->country = isset($options['country']) ? $options['country'] : $this->country;
        $this->language = isset($options['language']) ? $options['language'] : $this->language;
        $this->currency = isset($options['currency']) ? $options['currency'] : $this->currency;
    }
}