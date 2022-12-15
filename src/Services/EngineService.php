<?php

namespace SteamApi\Services;

class EngineService
{
    public static function setProxyForSingle(array $proxy): array
    {
        $curlData = [];

        if (array_key_exists('ip', $proxy) && array_key_exists('port', $proxy))
            $curlData[CURLOPT_PROXY] = $proxy['ip'].':'.$proxy['port'];
        else if (array_key_exists('domain_name', $proxy))
            $curlData[CURLOPT_PROXY] = $proxy['domain_name'];

        if (array_key_exists('user', $proxy) && array_key_exists('pass', $proxy))
            $curlData[CURLOPT_PROXYUSERPWD] = $proxy['user'].':'.$proxy['pass'];
        if (array_key_exists('type', $proxy))
            $curlData[CURLOPT_PROXYTYPE] = $proxy['type'];
        if (array_key_exists('timeout', $proxy))
            $curlData[CURLOPT_TIMEOUT] = $proxy['timeout'];
        if (array_key_exists('connect_timeout', $proxy))
            $curlData[CURLOPT_CONNECTTIMEOUT] = $proxy['connect_timeout'];
        if (array_key_exists('user_agent', $proxy))
            $curlData[CURLOPT_USERAGENT] = $proxy['user_agent'];

        return $curlData;
    }
}
