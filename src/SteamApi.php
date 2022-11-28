<?php

namespace SteamApi;

use Curl\ArrayUtil;
use SteamApi\Configs\Apps;
use SteamApi\Configs\Content;
use SteamApi\Configs\Economic;
use SteamApi\Configs\Engine;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Services\CookieService;
use SteamApi\Services\MixedService;

class SteamApi
{
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




    // -------------------------------------------- Useful methods --------------------------------------------

    /**
     * @return array
     */
    public function getCurrencyList(): array
    {
        return Economic::CURRENCY_LIST;
    }

    /**
     * @param $roundRobinArr
     * @return false|mixed
     */
    public function getNextItem(&$roundRobinArr)
    {
        return MixedService::getNextItem($roundRobinArr);
    }

    /**
     * @param string $browser
     * @return array|false|string[]
     */
    public function getUserAgent(string $browser = 'Chrome')
    {
        return ArrayUtil::arrayRandom(MixedService::getUserAgents($browser));
    }

    /**
     * @param string $browser
     * @return array|false|string[]
     */
    public function getUserAgents(string $browser = 'Chrome')
    {
        return MixedService::getUserAgents($browser);
    }

    /**
     * @return array
     */
    public function getImageSources(): array
    {
        return Content::IMAGE_SOURCES;
    }




    // ------------------------------------------- Inspects methods -------------------------------------------


    /**
     * @param string $inspectLink
     * @return mixed
     * @throws Exception\InvalidClassException
     */
    public function inspectItem(string $inspectLink)
    {
        $class = self::getClass('InspectItem', 'Inspectors');

        return (new $class($inspectLink))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param string $inspectLink
     * @return mixed
     * @throws Exception\InvalidClassException
     */
    public function inspectItemV2(string $inspectLink)
    {
        $class = self::getClass('InspectItemV2', 'Inspectors');

        return (new $class($inspectLink))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }




    // ------------------------------------------- Steam methods -------------------------------------------

    /**
     * @param int $appId
     * @param array $options
     * @return mixed
     * @throws Exception\InvalidClassException
     * @throws Exception\InvalidOptionsException
     */
    public function getItemListings(int $appId = Apps::CSGO_ID, array $options = [])
    {
        $class = self::getClass('ItemListings', 'Steam');

        return (new $class($appId, $options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param int $appId
     * @param array $options
     * @return mixed
     * @throws Exception\InvalidClassException
     * @throws Exception\InvalidOptionsException
     */
    public function getItemOrdersHistogram(int $appId = Apps::CSGO_ID, array $options = [])
    {
        $class = self::getClass('ItemOrdersHistogram', 'Steam');

        return (new $class($appId, $options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param int $appId
     * @param array $options
     * @return mixed
     * @throws Exception\InvalidClassException
     * @throws Exception\InvalidOptionsException
     */
    public function getSaleHistory(int $appId = Apps::CSGO_ID, array $options = [])
    {
        $class = self::getClass('SaleHistory', 'Steam');

        return (new $class($appId, $options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param int $appId
     * @param array $options
     * @return mixed
     * @throws Exception\InvalidClassException
     * @throws Exception\InvalidOptionsException
     */
    public function getItemNameId(int $appId = Apps::CSGO_ID, array $options = [])
    {
        $class = self::getClass('ItemNameId', 'Steam');

        return (new $class($appId, $options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response();
    }

    /**
     * @param int $appId
     * @param array $options
     * @return mixed
     * @throws Exception\InvalidClassException
     * @throws Exception\InvalidOptionsException
     */
    public function getItemPricing(int $appId = Apps::CSGO_ID, array $options = [])
    {
        $class = self::getClass('ItemPricing', 'Steam');

        return (new $class($appId, $options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param int $appId
     * @param array $options
     * @return mixed
     * @throws Exception\InvalidClassException
     */
    public function searchItems(int $appId = Apps::CSGO_ID, array $options = [])
    {
        $class = self::getClass('SearchItems', 'Steam');

        return (new $class($appId, $options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param int $appId
     * @param array $options
     * @return mixed
     * @throws Exception\InvalidClassException
     * @throws Exception\InvalidOptionsException
     */
    public function getUserInventory(int $appId = Apps::CSGO_ID, array $options = [])
    {
        $steamId = array_key_exists('steam_id', $options) ? $options['steam_id'] : null;

        $class = self::getClass('UserInventory', 'Steam');

        return (new $class($appId, $options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden, $this->withInspectData, $steamId);
    }

    /**
     * @param int $appId
     * @param array $options
     * @return mixed
     * @throws Exception\InvalidClassException
     * @throws Exception\InvalidOptionsException
     */
    public function getItemOrdersActivity(int $appId = Apps::CSGO_ID, array $options = [])
    {
        $class = self::getClass('ItemOrdersActivity', 'Steam');

        return (new $class($appId, $options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param array $options
     * @return mixed
     * @throws InvalidClassException
     */
    public function getNewlyListed(array $options = [])
    {
        $class = self::getClass('NewlyListed', 'Steam');

        return (new $class($options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param int|null $appId
     * @return mixed
     * @throws Exception\InvalidClassException
     */
    public function getAppFilters(int $appId = Apps::CSGO_ID)
    {
        $class = self::getClass('AppFilters', 'Steam');

        return (new $class($appId))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @return mixed
     * @throws InvalidClassException
     */
    public function getRecentlySold()
    {
        $class = self::getClass('RecentlySold', 'Steam');

        return (new $class())
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }





    // ------------------------------------------- Steam Auth methods -------------------------------------------

    /**
     * @param int $appId
     * @param array $options
     * @return mixed
     * @throws Exception\InvalidClassException
     * @throws Exception\InvalidOptionsException
     */
    public function getUserInventoryAuth(int $appId = Apps::CSGO_ID, array $options = [])
    {
        $steamId = array_key_exists('steam_id', $options) ? $options['steam_id'] : null;

        $class = self::getClass('UserInventory', 'SteamAuth');

        return (new $class($appId, $options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden, $this->withInspectData, $steamId);
    }

    /**
     * @return mixed
     * @throws InvalidClassException
     */
    public function getNotificationCounts()
    {
        $class = self::getClass('NotificationCounts', 'SteamAuth');

        return (new $class())
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts)
            ->response();
    }

    /**
     * @param array $options
     * @return mixed
     * @throws InvalidClassException
     */
    public function getMyListings(array $options = [])
    {
        $class = self::getClass('MyListings', 'SteamAuth');

        return (new $class($options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param array $options
     * @return mixed
     * @throws InvalidClassException
     */
    public function getMyHistory(array $options = [])
    {
        $class = self::getClass('MyHistory', 'SteamAuth');

        return (new $class($options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
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