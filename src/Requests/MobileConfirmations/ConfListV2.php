<?php

namespace SteamApi\Requests\MobileConfirmations;

use Carbon\Carbon;
use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Exception\InvalidOptionsException;
use SteamApi\Interfaces\RequestInterface;
use SteamTotp\SteamTotp;

class ConfListV2 extends Request implements RequestInterface
{
    const URL = "https://steamcommunity.com/mobileconf/conf?%s";

    private $method = 'GET';

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
        return sprintf(self::URL, self::generateConfQueryParams('conf'));
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Host' => 'steamcommunity.com',
            'Origin' => 'https://steamcommunity.com/',
            'X-Requested-With' => 'com.valvesoftware.android.steam.community'
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

        $this->serverTime = Carbon::now()->timestamp;
        $this->confirmationKey = SteamTotp::getConfirmationKey($this->identitySecret, $this->serverTime, 'conf');
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