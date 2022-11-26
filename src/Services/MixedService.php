<?php

namespace SteamApi\Services;

class MixedService
{
    /**
     * @param string $str
     * @return float|int
     */
    public static function toFloat(string $str)
    {
        if(preg_match("/([0-9\.,-]+)/", $str, $match)){
            $value = $match[0];
            if( preg_match("/(\.\d{1,2})$/", $value, $dot_delim) ){
                $value = (float)str_replace(',', '', $value);
            }
            else if( preg_match("/(,\d{1,2})$/", $value, $comma_delim) ){
                $value = str_replace('.', '', $value);
                $value = (float)str_replace(',', '.', $value);
            }
//            else if ( preg_match("/(,\d{3})$/", $value, $thousand_delim) )
//                $value = (int)str_replace(',', '', $value);
            else
                $value = (int)$value;
        }
        else {
            $value = 0;
        }

        return $value;
    }

    /**
     * @param array $roundRobinArr
     * @return false|mixed
     */
    public static function getNextItem(array &$roundRobinArr)
    {
        $curr = current($roundRobinArr);

        if ($curr === false)
            $curr = reset($roundRobinArr);

        next($roundRobinArr);
        return $curr;
    }

    /**
     * @param string $browser
     * @return array|false|string[]
     */
    public static function getUserAgents(string $browser = 'Chrome')
    {
        $thisDir = dirname(__FILE__);
        $parent_dir = realpath($thisDir . '/..');
        $path = $parent_dir . '/UserAgents/' . $browser . '.txt';
        $handle = @file_get_contents($path);

        return $handle ? explode("\n", $handle) : [];
    }
}