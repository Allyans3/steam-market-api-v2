<?php

namespace SteamApi\Requests\TradeOffers;

use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Exception\InvalidTradeLinkException;
use SteamApi\Interfaces\RequestInterface;
use SteamApi\Services\CookieService;
use SteamApi\Services\SteamService;
use SteamApi\Services\TradeOffersRequestService;

class CreateTradeOffer extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/tradeoffer/new/send";

    private $method = 'POST';

    private $tradeLink;
    private $formData;

    private $sessionId;

    /**
     * @param string $tradeLink
     * @param array $formData
     */
    public function __construct(string $tradeLink, array $formData)
    {
        $this->tradeLink = $tradeLink;
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
            'Referer' => $this->tradeLink
        ];
    }

    /**
     * @return array
     * @throws InvalidTradeLinkException
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
     * @throws InvalidTradeLinkException
     */
    private function formDataBuilder(): array
    {
        $partnerId = isset($this->formData['partner_id']) ? $this->formData['partner_id'] : "";
        $message = isset($this->formData['message']) ? $this->formData['message'] : "";

        $myItems = isset($this->formData['my_items']) ? $this->formData['my_items'] : [];
        $partnerItems = isset($this->formData['partner_items']) ? $this->formData['partner_items'] : [];

        return [
            'sessionid' => $this->sessionId,
            'serverid' => 1,
            'partner' => $partnerId ?: SteamService::toSteamId(self::getPartnerId()),
            'tradeoffermessage' => $message,
            'json_tradeoffer' => json_encode(TradeOffersRequestService::generateOfferData($myItems, $partnerItems)),
            'captcha' => null,
            'trade_offer_create_params' => json_encode(self::generateParams())
        ];
    }

    /**
     * @return mixed
     * @throws InvalidTradeLinkException
     */
    private function parseTradeLink()
    {
        $parsedTradeLink = parse_url($this->tradeLink, PHP_URL_QUERY);

        if (!$parsedTradeLink)
            throw new InvalidTradeLinkException("Invalid Trade Link");

        parse_str($parsedTradeLink, $result);

        if (!array_key_exists('partner', $result))
            throw new InvalidTradeLinkException("Trade link must contains 'partner' and optional 'token' parameters");

        return $result;
    }

    /**
     * @return mixed
     * @throws InvalidTradeLinkException
     */
    private function getPartnerId()
    {
        $parsedTradeLink = self::parseTradeLink();

        return $parsedTradeLink['partner'];
    }

    /**
     * @return array
     * @throws InvalidTradeLinkException
     */
    private function generateParams(): array
    {
        $parsedTradeLink = self::parseTradeLink();

        if (array_key_exists('token', $parsedTradeLink))
            return ['trade_offer_access_token' => $parsedTradeLink['token']];

        return [];
    }
}