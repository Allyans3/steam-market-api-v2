<?php

namespace SteamApi\Traits;

use SteamApi\Configs\Apps;
use SteamApi\Exception\InvalidClassException;

trait SteamAuthMethods
{
    /**
     * @param int $appId
     * @param array $options
     * @return mixed
     * @throws InvalidClassException
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
}