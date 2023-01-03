<?php

namespace SteamApi\Requests\MobileConfirmations;

use Carbon\Carbon;
use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Exception\InvalidOptionsException;
use SteamApi\Interfaces\RequestInterface;
use SteamTotp\SteamTotp;

class ConfAjax extends Request implements RequestInterface
{
    const REFERER = "https://steamcommunity.com/mobileconf/conf";
    const URL = "https://steamcommunity.com/mobileconf/ajaxop?op=%s&cid=%s&ck=%s&%s";

    private $method = 'GET';

    private $type = 'allow';
    private $confId = null;
    private $confKey = null;

    private $deviceId = null;
    private $steamId = null;
    private $confirmationKey = null;
    private $serverTime = null;
    private $identitySecret = null;

    /**
     * @param array $options
     * @throws InvalidOptionsException
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
        return sprintf(self::URL, $this->type, $this->confId, $this->confKey, self::generateConfQueryParams($this->type));
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Host' => 'steamcommunity.com',
            'Origin' => 'https://steamcommunity.com/',
            'Referer' => self::REFERER . '?' . self::generateConfQueryParams('conf')
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
     * @throws InvalidOptionsException
     */
    private function setOptions(array $options)
    {
        if (isset($options['device_id']))
            $this->deviceId = $options['device_id'];
        else
            throw new InvalidOptionsException("Option 'device_id' must be filled");

        if (isset($options['steam_id']))
            $this->steamId = $options['steam_id'];
        else
            throw new InvalidOptionsException("Option 'steam_id' must be filled");

        if (isset($options['identity_secret']))
            $this->identitySecret = $options['identity_secret'];
        else
            throw new InvalidOptionsException("Option 'identity_secret' must be filled");

        if (isset($options['conf_id']))
            $this->confId = $options['conf_id'];
        else
            throw new InvalidOptionsException("Option 'conf_id' must be filled");

        if (isset($options['conf_key']))
            $this->confKey = $options['conf_key'];
        else
            throw new InvalidOptionsException("Option 'conf_key' must be filled");

        $this->type = isset($options['type']) ? $options['type'] : $this->type;

        $this->serverTime = Carbon::now()->timestamp;
        $this->confirmationKey = SteamTotp::getConfirmationKey($this->identitySecret, $this->serverTime, $this->type);
    }

    /**
     * @param string $tag
     * @return string
     */
    private function generateConfQueryParams(string $tag): string
    {
        return http_build_query([
            'p' => $this->deviceId,
            'a' => $this->steamId,
            'k' => $this->confirmationKey,
            't' => $this->serverTime,
            'm' => 'android',
            'tag' => $tag
        ]);
    }
}