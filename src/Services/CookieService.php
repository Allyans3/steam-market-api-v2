<?php

namespace SteamApi\Services;

class CookieService
{
    /**
     * @param string $cookie
     * @return array
     */
    public static function strToArray(string $cookie): array
    {
        $cookieArr = [];

        $expCookie = explode(';', $cookie);

        foreach ($expCookie as $item) {
            $expItem = explode('=', trim($item));

            $cookieArr[$expItem[0]] = $expItem[1];
        }

        return $cookieArr;
    }

    /**
     * @param array $cookie
     * @return string
     */
    public static function arrayToStr(array $cookie): string
    {
        $cookieStr = '';

        foreach ($cookie as $key => $value)
            $cookieStr .= "$key=$value; ";

        return trim($cookieStr);
    }
}