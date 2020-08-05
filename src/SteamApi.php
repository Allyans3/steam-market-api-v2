<?php

namespace SteamApi;

use Psy\Exception\RuntimeException;
use SteamApi\Config\Config;

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

    public function inspectItem($inspectLink)
    {
        $type = 'InspectItem';

        $class = self::CLASS_PREFIX . $type;

        if (!class_exists($class)) {
            throw new RuntimeException('Call to undefined request type');
        }

        return (new $class($inspectLink))->call()->response();
    }

    public function getItemPricing(int $appId = null, array $options = [])
    {
        $type = 'ItemPricing';

        return $this->request($type, $appId, $options)->call($options)->response();
    }

    public function getMarketListings(int $appId = null, array $options = [])
    {
        $type = 'MarketListings';

        return $this->request($type, $appId, $options)->call($options)->response();
    }

    public function getSaleHistory(int $appId = null, array $options = [])
    {
        $type = 'SaleHistory';

        return $this->request($type, $appId, $options)->call($options)->response();
    }

    public function searchItems(int $appId = null, array $options = [])
    {
        $type = 'SearchItems';

        return $this->request($type, $appId, $options)->call($options)->response();
    }

    public function getItemListings(int $appId = null, array $options = [])
    {
        $type = 'ItemListings';

        return $this->request($type, $appId, $options)->call($options)->response();
    }

    public function getItemNameId(int $appId = null, array $options = [])
    {
        $type = 'ItemNameId';

        return $this->request($type, $appId, $options)->call($options)->response();
    }

    public function getItemOrdersHistogram(array $options = [])
    {
        $type = 'ItemOrdersHistogram';

        $class = self::CLASS_PREFIX . $type;

        if (!class_exists($class)) {
            throw new RuntimeException('Call to undefined request type');
        }

        return (new $class($options))->call()->response();
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