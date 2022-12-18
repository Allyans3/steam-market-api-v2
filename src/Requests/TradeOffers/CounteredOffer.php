<?php

namespace SteamApi\Requests\TradeOffers;

use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Exception\InvalidFormDataException;
use SteamApi\Interfaces\RequestInterface;
use SteamApi\Services\CookieService;
use SteamApi\Services\TradeOffersRequestService;

class CounteredOffer extends Request implements RequestInterface
{
    const REFERER = "https://steamcommunity.com/tradeoffer/%s/";
    const URL = "https://steamcommunity.com/tradeoffer/new/send";

    private $method = 'POST';

    private $tradeOfferId;
    private $formData;

    private $sessionId;

    /**
     * @param string $tradeLink
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
        return self::URL;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Host' => 'steamcommunity.com',
            'Origin' => 'https://steamcommunity.com/',
            'Referer' => sprintf(self::URL, $this->tradeOfferId)
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

        $message = isset($this->formData['message']) ? $this->formData['message'] : "";

        $myItems = isset($this->formData['my_items']) ? $this->formData['my_items'] : [];
        $partnerItems = isset($this->formData['partner_items']) ? $this->formData['partner_items'] : [];

        return [
            'sessionid' => $this->sessionId,
            'serverid' => 1,
            'partner' => $partnerId,
            'tradeoffermessage' => $message,
            'json_tradeoffer' => json_encode(TradeOffersRequestService::generateOfferData($myItems, $partnerItems)),
            'captcha' => null,
            'trade_offer_create_params' => json_encode([]),
            'tradeofferid_countered' => $this->tradeOfferId
        ];
    }
}