<?php

namespace SteamApi\Requests\TradeOffers;

use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Exception\InvalidFormDataException;
use SteamApi\Interfaces\PostRequestInterface;
use SteamApi\Services\CookieService;

class AcceptTradeOffer extends Request implements PostRequestInterface
{
    const REFERER = "https://steamcommunity.com/tradeoffer/%s";
    const URL = "https://steamcommunity.com/tradeoffer/%s/accept";

    private $method = 'POST';

    private $tradeOfferId;
    private $formData;

    private $sessionId;

    /**
     * @param $tradeOfferId
     * @param array $formData
     */
    public function __construct($tradeOfferId, array $formData)
    {
        $this->tradeOfferId = $tradeOfferId;
        $this->formData = $formData;
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
        return [
            'Host' => 'steamcommunity.com',
            'Origin' => 'https://steamcommunity.com/',
            'Referer' => sprintf(self::REFERER, $this->tradeOfferId)
        ];
    }

    /**
     * @return array
     * @throws InvalidFormDataException
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
     * @throws InvalidFormDataException
     */
    private function formDataBuilder(): array
    {
        if (isset($this->formData['partner_id']))
            $partnerId = $this->formData['partner_id'];
        else
            throw new InvalidFormDataException("Form Data 'partner_id' must be filled");

        return [
            'sessionid' => $this->sessionId,
            'serverid' => 1,
            'tradeofferid' => $this->tradeOfferId,
            'partner' => $partnerId,
            'captcha' => null
        ];
    }
}