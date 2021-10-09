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

    public function inspectItem(array $options, $proxy = [])
    {
        $type = 'InspectItem';

        $class = self::CLASS_PREFIX . $type;

        if (!class_exists($class)) {
            throw new RuntimeException('Call to undefined request type');
        }

        $detailed = $options['detailed'] ?? false;

        return (new $class($options))->call($proxy, $detailed)->response();
    }

    public function inspectItemV2(array $options, $proxy = [])
    {
        $type = 'InspectItemV2';

        $class = self::CLASS_PREFIX . $type;

        if (!class_exists($class)) {
            throw new RuntimeException('Call to undefined request type');
        }

        $detailed = $options['detailed'] ?? false;

        return (new $class($options))->call($proxy, $detailed)->response();
    }

    public function getItemPricing(int $appId, array $options = [], array $proxy = [])
    {
        $type = 'ItemPricing';

        $detailed = $options['detailed'] ?? false;

        return $this->request($type, $appId, $options)->call($proxy, $detailed)->response();
    }

    public function getMarketListings(int $appId, array $options = [], array $proxy = [])
    {
        $type = 'MarketListings';

        $detailed = $options['detailed'] ?? false;

        return $this->request($type, $appId, $options)->call($proxy, $detailed)->response();
    }

    public function getSaleHistory(int $appId, array $options = [], array $proxy = [])
    {
        $type = 'SaleHistory';

        $detailed = $options['detailed'] ?? false;

        return $this->request($type, $appId, $options)->call($proxy, $detailed)->response();
    }

    public function searchItems(int $appId, array $options = [], array $proxy = [])
    {
        $type = 'SearchItems';

        $detailed = $options['detailed'] ?? false;

        return $this->request($type, $appId, $options)->call($proxy, $detailed)->response();
    }

    public function getItemListings(int $appId, array $options = [], array $proxy = [])
    {
        $type = 'ItemListings';

        $detailed = $options['detailed'] ?? false;
        $multi = $options['multi'] ?? false;

        return $this->request($type, $appId, $options)->call($proxy, $detailed, $multi)->response();
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

    public function testProxy(array $proxy)
    {
        $type = 'ProxyTester';

        $class = self::CLASS_PREFIX . $type;

        if (!class_exists($class)) {
            throw new RuntimeException('Call to undefined request type');
        }

        return (new $class())->call($proxy)->response();
    }

    public function getNewlyListed(array $options = [], array $proxy = [])
    {
        $type = 'NewlyListed';

        $class = self::CLASS_PREFIX . $type;

        if (!class_exists($class)) {
            throw new RuntimeException('Call to undefined request type');
        }

        $detailed = $options['detailed'] ?? false;

        return (new $class($options))->call($proxy, $detailed)->response();
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