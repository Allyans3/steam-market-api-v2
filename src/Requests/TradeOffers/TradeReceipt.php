<?php

namespace SteamApi\Requests\TradeOffers;

use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Interfaces\RequestInterface;

class TradeReceipt extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/trade/%s/receipt/?l=%s";

    private $method = 'GET';

    private $tradeOfferId;
    private $language = 'english';

    /**
     * @param $tradeOfferId
     * @param array $options
     */
    public function __construct($tradeOfferId, array $options)
    {
        $this->tradeOfferId = $tradeOfferId;
        $this->setOptions($options);
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return sprintf(self::URL, $this->tradeOfferId, $this->language);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [];
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
        $this->language = isset($options['language']) ? $options['language'] : $this->language;
    }
}