<?php

namespace SteamApi\Requests\MobileConfirmations;

use Carbon\Carbon;
use SteamApi\Engine\Request;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Exception\InvalidOptionsException;
use SteamApi\Interfaces\PostRequestInterface;
use SteamTotp\SteamTotp;

class MultiConfAjax extends Request implements PostRequestInterface
{
    const REFERER = "https://steamcommunity.com/mobileconf/conf";
    const URL = "https://steamcommunity.com/mobileconf/multiajaxop";

    private $method = 'POST';

    private $type = 'allow';
    private $confIds = [];
    private $confKeys = [];

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
            'Referer' => self::REFERER . '?' . http_build_query(self::generateConfQueryParams('conf'))
        ];
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

        if (isset($options['confirmations'])) {
            foreach ($options['confirmations'] as $con) {
                $this->confIds[] = $con['id'];
                $this->confKeys[] = $con['key'];
            }
        }

        $this->serverTime = Carbon::now()->timestamp;
        $this->confirmationKey = SteamTotp::getConfirmationKey($this->identitySecret, $this->serverTime, $this->type);
    }

    /**
     * @return array
     */
    private function formDataBuilder(): array
    {
        return array_merge(self::generateConfQueryParams($this->type),
            [
                'op' => $this->type,
                'cid' => $this->confIds,
                'ck' => $this->confKeys,
            ]
        );
    }

    /**
     * @param string $tag
     * @return array
     */
    private function generateConfQueryParams(string $tag): array
    {
        return [
            'p' => $this->deviceId,
            'a' => $this->steamId,
            'k' => $this->confirmationKey,
            't' => $this->serverTime,
            'm' => 'android',
            'tag' => $tag
        ];
    }
}