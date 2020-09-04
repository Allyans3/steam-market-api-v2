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
        $asset = $item['asset'];

        if (array_key_exists('market_actions', $asset)) {
            $listing_id = $item['listingid'];
            $asset_id = $asset['id'];

            $inspect_link = $asset['market_actions'][0]['link'];

            $inspect_link = str_replace("%listingid%", $listing_id, $inspect_link);
            $inspect_link = str_replace("%assetid%", $asset_id, $inspect_link);

            return $inspect_link;
        }

        return "";
    }
}