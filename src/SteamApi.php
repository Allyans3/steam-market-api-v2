<?php

namespace SteamApi;

use SteamApi\Configs\Engine;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Traits\InspectsMethods;
use SteamApi\Services\CookieService;
use SteamApi\Traits\MobileConfirmationMethods;
use SteamApi\Traits\SteamAuthMethods;
use SteamApi\Traits\SteamMethods;
use SteamApi\Traits\TradeOfferMethods;
use SteamApi\Traits\UsefulMethods;
use SteamApi\Traits\UsefulMobileConfMethods;

class SteamApi
{
    use InspectsMethods, SteamMethods, UsefulMethods, SteamAuthMethods, TradeOfferMethods, MobileConfirmationMethods,
        UsefulMobileConfMethods;

    private $detailed = false;

    private $multiRequest = false;

    private $proxy = [];

    private $select = [];
    private $makeHidden = [];
    private $withInspectData = false;

    private $cookies = '';

    private $curlOpts = [];


    /**
     * @return SteamApi
     */
    public static function query(): SteamApi
    {
        return new self();
    }


    /**
     * @param array $proxy
     * @return $this
     */
    public function withProxy(array $proxy): SteamApi
    {
        $this->proxy = $proxy;
        return $this;
    }

    /**
     * @return $this
     */
    public function detailed(): SteamApi
    {
        $this->detailed = true;
        return $this;
    }

    /**
     * @return $this
     */
    public function multiRequest(): SteamApi
    {
        $this->multiRequest = true;
        return $this;
    }

    /**
     * @param array $select
     * @return $this
     */
    public function select(array $select): SteamApi
    {
        $this->select = $select;
        return $this;
    }

    /**
     * @param array $makeHidden
     * @return $this
     */
    public function makeHidden(array $makeHidden): SteamApi
    {
        $this->makeHidden = $makeHidden;
        return $this;
    }

    /**
     * @param $cookie
     * @return $this
     */
    public function withCookies($cookie): SteamApi
    {
        if (is_array($cookie))
            $this->cookies = CookieService::arrayToStr($cookie);
        else
            $this->cookies = $cookie;

        return $this;
    }

    /**
     * @param array $curlOpts
     * @return $this
     */
    public function withCustomCurlOpts(array $curlOpts): SteamApi
    {
        $this->curlOpts = $curlOpts;
        return $this;
    }

    /**
     * Only for UserInventory and UserInventoryAuth
     *
     * @return $this
     */
    public function withInspectData(): SteamApi
    {
        $this->withInspectData = true;
        return $this;
    }



    /**
     * @param string $name
     * @param string $dir
     * @return mixed
     * @throws InvalidClassException
     */
    private function getClass(string $name, string $dir)
    {
        $class = Engine::REQUEST_PREFIX . $dir . '\\' . $name;

        if (!class_exists($class))
            throw new InvalidClassException('Call to undefined Request Class');

        return $class;
    }
}