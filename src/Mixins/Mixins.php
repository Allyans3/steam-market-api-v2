<?php

namespace SteamApi\Mixins;

use SteamApi\Config\Config;

class Mixins
{
    public static function getCondition($market_name)
    {
        foreach (Config::CONDITIONS as $key => $value) {
            if (strpos($market_name, $key)) {
                return Config::CONDITIONS[$key];
            }
        }

        return '';
    }

    public static function fillBaseData($data)
    {
        return [
            'start'       => $data['start'],
            'pagesize'    => $data['pagesize'],
            'total_count' => $data['total_count']
        ];
    }

    public static function generateInspectLink($item)
    {
        $listing_id = $item['listingid'];
        $asset_id = $item['asset']['id'];

        $inspect_link = $item['asset']['market_actions'][0]['link'];

        $inspect_link = str_replace("%listingid%", $listing_id, $inspect_link);
        $inspect_link = str_replace("%assetid%", $asset_id, $inspect_link);

        return $inspect_link;
    }
}