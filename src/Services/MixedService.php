<?php

namespace SteamApi\Services;

class MixedService
{
    /**
     * @param $str
     * @return float|int
     */
    public static function toFloat($str)
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
}