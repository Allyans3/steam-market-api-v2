<?php

namespace SteamApi\Traits;

use SteamApi\Configs\Apps;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Exception\InvalidOptionsException;

trait SteamMethods
{
    /**
     * @param int $appId
     * @param array $options
     * @return mixed
     * @throws InvalidClassException
     * @throws InvalidOptionsException
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
     * @throws InvalidClassException
     * @throws InvalidOptionsException
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
     * @throws InvalidClassException
     * @throws InvalidOptionsException
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
     * @throws InvalidClassException
     * @throws InvalidOptionsException
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
     * @throws InvalidClassException
     * @throws InvalidOptionsException
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
     * @throws InvalidClassException
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
     * @throws InvalidClassException
     * @throws InvalidOptionsException
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
     * @throws InvalidClassException
     * @throws InvalidOptionsException
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
     * @return mixed
     * @throws InvalidClassException
     */
    public function getQueryTime()
    {
        $class = self::getClass('QueryTime', 'Steam');

        return (new $class())
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param int|null $appId
     * @return mixed
     * @throws InvalidClassException
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
}