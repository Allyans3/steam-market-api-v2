<?php

namespace SteamApi;

use Psy\Exception\RuntimeException;
use SteamApi\Config\Config;
use SteamApi\Mixins\Mixins;

class SteamApi
{
    const CLASS_PREFIX = '\\SteamApi\\Requests\\';

    public function getCurrencyList(): array
    {
        return Config::CURRENCY;
    }

    public function getConditionList(): array
    {
        return Config::CONDITIONS;
    }

    public function getStickersPosition(): array
    {
        return Config::STICKERS_POS;
    }

    public function getUserAgents(string $browser = 'Chrome')
    {
        return Mixins::getUserAgents($browser);
    }

    public function getNextIp(&$proxyList)
    {
        return Mixins::getNextIp($proxyList);
    }

    public function inspectItem(string $inspectLink)
    {
        $type = 'InspectItem';

        $class = self::CLASS_PREFIX . $type;

        if (!class_exists($class)) {
            throw new RuntimeException('Call to undefined request type');
        }

        return (new $class($inspectLink))->call()->response();
    }

    public function getItemPricing(int $appId, array $options = [], array $proxy = [])
    {
        $type = 'ItemPricing';

        return $this->request($type, $appId, $options)->call($proxy)->response();
    }

    public function getMarketListings(int $appId, array $options = [], array $proxy = [])
    {
        $type = 'MarketListings';

        return $this->request($type, $appId, $options)->call($proxy)->response();
    }

    public function getSaleHistory(int $appId, array $options = [], array $proxy = [])
    {
        $type = 'SaleHistory';

        return $this->request($type, $appId, $options)->call($proxy)->response();
    }

    public function searchItems(int $appId, array $options = [], array $proxy = [])
    {
        $type = 'SearchItems';

        return $this->request($type, $appId, $options)->call($proxy)->response();
    }

    public function getItemListings(int $appId, array $options = [], array $proxy = [])
    {
        $type = 'ItemListings';

        return $this->request($type, $appId, $options)->call($proxy)->response();
    }

    public function getItemNameId(int $appId, array $options = [], array $proxy = [])
    {
        $type = 'ItemNameId';

        return $this->request($type, $appId, $options)->call($proxy)->response();
    }

    public function getItemOrdersHistogram(array $options = [], array $proxy = [])
    {
        $type = 'ItemOrdersHistogram';

        $class = self::CLASS_PREFIX . $type;

        if (!class_exists($class)) {
            throw new RuntimeException('Call to undefined request type');
        }

        return (new $class($options))->call($proxy)->response();
    }

    public function getUserInventory(int $appId, array $options = [], array $proxy = [])
    {
        $type = 'UserInventory';

        return $this->request($type, $appId, $options)->call($proxy)->response();
    }

    public function getUserInventoryV2(int $appId, array $options = [], array $proxy = [])
    {
        $type = 'UserInventoryV2';

        return $this->request($type, $appId, $options)->call($proxy)->response();
    }



    private function request(string $type, int $appId, array $options = [])
    {
        $class = self::CLASS_PREFIX . $type;

        if (!class_exists($class)) {
            throw new RuntimeException('Call to undefined request type');
        }

        return new $class($appId, $options);
    }
}