<?php

namespace SteamApi;

use Psy\Exception\RuntimeException;

class SteamApi
{
    const CLASS_PREFIX = '\\SteamApi\\Requests\\';

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