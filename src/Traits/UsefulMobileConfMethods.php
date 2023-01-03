<?php

namespace SteamApi\Traits;

use Ramsey\Uuid\Uuid;
use SteamApi\Services\MobileConfService;
use SteamTotp\SteamTotp;

trait UsefulMobileConfMethods
{
    /**
     * @param $path
     * @return false|mixed
     */
    public function parseMaFile($path)
    {
        return MobileConfService::parseMaFile($path);
    }

    /**
     * @param string $version
     * @return string
     */
    public function getDeviceIdByUuidVersion(string $version = 'v4'): string
    {
        switch ($version) {
            case 'v1':
                $uuid = Uuid::uuid1()->toString();
                break;
            case 'v6':
                $uuid = Uuid::uuid6()->toString();
                break;
            default:
                $uuid = Uuid::uuid4()->toString();
        }

        return "android:$uuid";
    }

    /**
     * @param string|int $steamid Your SteamID in 64-bit format (as a string or integer)
     * @return string
     */
    public function getDeviceIdBySteamId($steamId): string
    {
        return SteamTotp::getDeviceID($steamId);
    }
}