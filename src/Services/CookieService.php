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
            if (!$item)
                continue;

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

    /**
     * @param $string
     * @param null $domain
     * @return array
     */
    public static function parseCookieFile($string): array
    {
        $lines = explode(PHP_EOL, $string);

        $cookies = [];

        foreach ($lines as $line) {
            $cookie = [];

            if (substr($line, 0, 10) == '#HttpOnly_') {
                $line = substr($line, 10);
                $cookie['httponly'] = true;
            } else
                $cookie['httponly'] = false;

            if(strlen( $line ) > 0 && $line[0] != '#' && substr_count($line, "\t") == 6) {
                $tokens = explode("\t", $line);

                $tokens = array_map('trim', $tokens);

                $cookie['domain'] = $tokens[0];
                $cookie['flag'] = $tokens[1];
                $cookie['path'] = $tokens[2];
                $cookie['secure'] = $tokens[3];

                $cookie['expiration-epoch'] = $tokens[4];
                $cookie['name'] = urldecode($tokens[5]);
                $cookie['value'] = urldecode($tokens[6]);

                $cookie['expiration'] = date('Y-m-d h:i:s', $tokens[4]);

                $cookies[] = $cookie;
            }
        }

        return $cookies;
    }

    /**
     * @param array $cookies
     * @param string $domain
     * @param string $name
     * @return mixed|null
     */
    public static function getNameValueByDomain(array $cookies, string $domain, string $name)
    {
        foreach ($cookies as $cookie) {
            if ($cookie['domain'] === $domain && $cookie['name'] === $name)
                return $cookie['value'];
        }

        return "";
    }

    /**
     * @param string $cookies
     * @param array $curlOpts
     * @return mixed|string|null
     */
    public static function parseSessionId(string $cookies, array $curlOpts)
    {
        if ($cookies) {
            $arrCookies = self::strToArray($cookies);

            if (array_key_exists('sessionid', $arrCookies))
                return $arrCookies['sessionid'];
        }

        if (array_key_exists(CURLOPT_COOKIEFILE, $curlOpts)) {
            $fileContent = file_get_contents($curlOpts[CURLOPT_COOKIEFILE]);

            if ($fileContent) {
                $arrCookies = self::parseCookieFile($fileContent);

                return self::getNameValueByDomain($arrCookies, 'steamcommunity.com', 'sessionid');
            }
        }

        return "";
    }
}