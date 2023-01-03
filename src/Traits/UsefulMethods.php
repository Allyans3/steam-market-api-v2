<?php

namespace SteamApi\Traits;

use Curl\ArrayUtil;
use SteamApi\Configs\Content;
use SteamApi\Configs\Economic;
use SteamApi\Services\MixedService;

trait UsefulMethods
{
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
}