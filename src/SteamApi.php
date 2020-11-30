<?php

namespace SteamApi;

use Psy\Exception\RuntimeException;
use SteamApi\Config\Config;
use SteamApi\Mixins\Mixins;

class SteamApi
{
    const CLASS_PREFIX = '\\SteamApi\\Requests\\';

    public function getCurrencyList()
    {
        return Config::CURRENCY;
    }

    public function getConditionList()
    {
        return Config::CONDITIONS;
    }

    public function getStickersPosition()
    {
        return Config::STICKERS_POS;
    }

    public function getUserAgents($browser = 'Chrome')
    {
        return Mixins::getUserAgents($browser);
    }

    public function getNextIp(&$proxyList)
    {
        return Mixins::getNextIp($proxyList);
    }

    public function inspectItem($inspectLink)
    {
        $type = 'InspectItem';

        $class = self::CLASS_PREFIX . $type;

        if (!class_exists($class)) {
            throw new RuntimeException('Call to undefined request type');
        }

        return (new $class($inspectLink))->call()->response();
    }

    public function getItemPricing(int $appId = null, array $options = [], $proxy = [])
    {
        $type = 'ItemPricing';

        return $this->request($type, $appId, $options)->call($options, $proxy)->response();
    }

    public function getMarketListings(int $appId = null, array $options = [], $proxy = [])
    {
        $type = 'MarketListings';

        return $this->request($type, $appId, $options)->call($options, $proxy)->response();
    }

    public function getSaleHistory(int $appId = null, array $options = [], $proxy = [])
    {
        $type = 'SaleHistory';

        return $this->request($type, $appId, $options)->call($options, $proxy)->response();
    }

    public function searchItems(int $appId = null, array $options = [], $proxy = [])
    {
        $type = 'SearchItems';

        return $this->request($type, $appId, $options)->call($options, $proxy)->response();
    }

    public function getItemListings(int $appId = null, array $options = [], $proxy = [])
    {
        $type = 'ItemListings';

        return $this->request($type, $appId, $options)->call($options, $proxy)->response();
    }

    public function getItemNameId(int $appId = null, array $options = [], $proxy = [])
    {
        $type = 'ItemNameId';

        return $this->request($type, $appId, $options)->call($options, $proxy)->response();
    }

    public function getItemOrdersHistogram(array $options = [], $proxy = [])
    {
        $type = 'ItemOrdersHistogram';

        $class = self::CLASS_PREFIX . $type;

        if (!class_exists($class)) {
            throw new RuntimeException('Call to undefined request type');
        }

        return (new $class($options))->call($proxy)->response();
    }

    public function getUserInventory(int $appId = null, array $options = [], $proxy = [])
    {
        $type = 'UserInventory';

        return $this->request($type, $appId, $options)->call($options, $proxy)->response();
    }



    private function request($type, $appId, array $options)
    {
        if (!$appId) {
            throw new RuntimeException('Application ID not defined');
        }

        $class = self::CLASS_PREFIX . $type;

        if (!class_exists($class)) {
            throw new RuntimeException('Call to undefined request type');
        }

        return new $class($appId, $options);
    }
}