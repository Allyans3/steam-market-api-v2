<?php

namespace SteamApi\Services;

class SteamService
{
    /**
     * @param $id
     * @return float|int|mixed|string
     */
    public static function toPartnerId($id)
    {
        if (preg_match('/^STEAM_/', $id)) {
            $split = explode(':', $id);
            return $split[2] * 2 + $split[1];
        } elseif (preg_match('/^765/', $id) && strlen($id) > 15)
            return bcsub($id, '76561197960265728');

        return $id;
    }

    /**
     * @param $id
     * @return mixed|string
     */
    public static function toSteamId($id)
    {
        if (preg_match('/^STEAM_/', $id)) {
            $parts = explode(':', $id);
            return bcadd(bcadd(bcmul($parts[2], '2'), '76561197960265728'), $parts[1]);
        } elseif (is_numeric($id) && strlen($id) < 16)
            return bcadd($id, '76561197960265728');

        return $id;
    }
}