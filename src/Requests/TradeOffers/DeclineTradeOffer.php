<?php

namespace SteamApi\Requests\TradeOffers;

use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Interfaces\PostRequestInterface;
use SteamApi\Services\CookieService;

class DeclineTradeOffer extends Request implements PostRequestInterface
{
    const URL = "https://steamcommunity.com/tradeoffer/%s/decline";

    private $method = 'POST';

    private $tradeOfferId;
    private $sessionId;

    /**
     * @param $tradeOfferId
     */
    public function __construct($tradeOfferId)
    {
        $this->tradeOfferId = $tradeOfferId;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return sprintf(self::URL, $this->tradeOfferId);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getFormData(): array
    {
        return self::formDataBuilder();
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
        $this->sessionId = CookieService::parseSessionId($cookies, $curlOpts);

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
     * @return array
     */
    private function formDataBuilder(): array
    {
        return [
            'sessionid' => $this->sessionId,
        ];
    }
}