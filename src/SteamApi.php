<?php

namespace SteamApi;

use Psy\Exception\RuntimeException;

class SteamApi
{
//    /**
//     * @param int|null $appId
//     * @param array $options
//     * @return mixed
//     */
//    public function getItemPricing(int $appId = null, array $options = [])
//    {
//        if (!$appId) {
//            throw new RuntimeException('Application ID not defined');
//        }
//
//        return new ItemPricing($appId, $options);
//    }

    const CLASS_PREFIX = '\\SteamApi\\Requests\\';

    public function getItemPricing(int $appId = null, array $options = [])
    {
        $type = 'ItemPricing';

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