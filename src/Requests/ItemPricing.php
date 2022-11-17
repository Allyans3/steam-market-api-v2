<?php

namespace SteamApi\Requests;

use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Exception\InvalidOptionsException;
use SteamApi\Interfaces\RequestInterface;

class ItemPricing extends Request implements RequestInterface
{
    const URL = 'https://steamcommunity.com/market/priceoverview/?country=%s&currency=%s&appid=%s&market_hash_name=%s';

    private $method = 'GET';

    private $appId;
    private $marketHashName = '';

    private $country = 'US';
    private $currency = 1;

    /**
     * @param $appId
     * @param array $options
     * @throws InvalidOptionsException
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
        return sprintf(self::URL, $this->country, $this->currency, $this->appId, $this->marketHashName);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Host' => 'steamcommunity.com',
            'Origin' => 'https://steamcommunity.com/',
//            'If-Modified-Since' => Carbon::now('UTC')->subSeconds(30)->toRfc7231String(),
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
     * @throws InvalidOptionsException
     */
    private function setOptions($options)
    {
        if (isset($options['market_hash_name']))
            $this->marketHashName = rawurlencode($options['market_hash_name']);
        else
            throw new InvalidOptionsException("Option 'market_hash_name' must be filled");

        $this->country = isset($options['country']) ? $options['country'] : $this->country;
        $this->currency = isset($options['currency']) ? $options['currency'] : $this->currency;
    }
}