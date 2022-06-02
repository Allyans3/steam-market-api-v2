<?php

namespace SteamApi\Mixins;

use DiDom\Document;
use SteamApi\Config\Config;

class Mixins
{
    public static function getExterior($market_name): string
    {
        foreach (Config::EXTERIOR_LIST as $key => $value) {
            if (strpos($market_name, $key)) {
                return Config::EXTERIOR_LIST[$key];
            }
        }

        return '';
    }

    public static function fillBaseData($data): array
    {
        return [
            'start'       => $data['start'],
            'pagesize'    => $data['pagesize'],
            'total_count' => $data['total_count'],
            'items'       => []
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
            else
                $value = (int)$value;
        }
        else {
            $value = 0;
        }

        return $value;
    }

    public static function getUserAgents($browser = 'Chrome')
    {
        $thisDir = dirname(__FILE__);
        $parent_dir = realpath($thisDir . '/..');
        $path = $parent_dir . '/UserAgents/' . $browser . '.txt';
        $handle = @file_get_contents($path);

        return $handle ? explode("\n", $handle) : [];
    }

    public static function getNextIp(&$proxyList)
    {
        $current = current($proxyList);

        if ($current === false)
            $current = reset($proxyList);

        next($proxyList);
        return $current;
    }

    public static function createSteamLink($asset, $description): array
    {
        $returnData = [
            'inspectLink' => '',
            'inspectable' => false
        ];

        $typeList = [
            "Pistol",
            "SMG",
            "Rifle",
            "Sniper Rifle",
            "Shotgun",
            "Machinegun",
            "Knife",
            "Gloves",
            "Agent"
        ];

        if (is_array($asset))
            $assetId = $asset['assetid'];
        else
            $assetId = $asset;

        foreach ($typeList as $value) {
            if (str_contains($description['type'], $value))
                $returnData['inspectable'] = true;
        }

        if (array_key_exists('actions', $description)) {
            $link = $description['actions'][0]['link'];
            $steamId = explode('/', $link)[4];
            $link = str_replace("%assetid%", $assetId, $link);
            $link = str_replace("%owner_steamid%", $steamId, $link);

            $returnData['inspectLink'] = $link;
        }

        return $returnData;
    }

    public static function createNewlyListedInspectLink($asset): array
    {
        $returnData = [
            'inspectLink' => '',
            'inspectable' => false
        ];

        $typeList = [
            "Pistol",
            "SMG",
            "Rifle",
            "Sniper Rifle",
            "Shotgun",
            "Machinegun",
            "Knife",
            "Gloves",
            "Agent"
        ];

        foreach ($typeList as $value) {
            if (str_contains($asset['type'], $value))
                $returnData['inspectable'] = true;
        }

        if (array_key_exists('market_actions', $asset)) {
            $link = $asset['market_actions'][0]['link'];
            $link = str_replace("%assetid%", $asset['id'], $link);

            $returnData['inspectLink'] = $link;
        }

        return $returnData;
    }

    public static function parseStickers($descriptions)
    {
        $stickers = [];

        foreach ($descriptions as $description) {
            if (!trim($description['value']))
                continue;

            $document = new Document($description['value']);
            $rawNode = $document->find('#sticker_info');

            foreach ($rawNode as $node) {
                $stickersText = trim($node->text());
                $stickersStr = str_replace('Sticker: ', '', $stickersText);
                $stickersStr = str_replace('Patch: ', '', $stickersStr);

                $stickers = explode(', ', $stickersStr);
            }
        }

        return $stickers;
    }

    public static function parseNameTag($nameTag): string
    {
        $matches = explode("''", $nameTag);

        return $matches[1];
    }

    public static function arrayDiffAssocRecursive($array1, $array2)
    {
        foreach($array1 as $key => $value)
        {
            if(is_array($value)) {
                if(!isset($array2[$key])) {
                    $difference[$key] = $value;
                }
                else if(!is_array($array2[$key]))
                {
                    $difference[$key] = $value;
                } else {
                    $new_diff = self::arrayDiffAssocRecursive($value, $array2[$key]);

                    if($new_diff != false)
                        $difference[$key] = $new_diff;
                }
            } elseif(!isset($array2[$key]) || $array2[$key] != $value) {
                $difference[$key] = $value;
            }
        }
        return $difference ?? false;
    }
}