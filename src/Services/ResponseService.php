<?php

namespace SteamApi\Services;

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;

class ResponseService
{
    /**
     * @param $data
     * @return array
     */
    public static function fillBaseData($data): array
    {
        return [
            'start'       => $data['start'],
            'page_size'   => $data['pagesize'],
            'total_count' => $data['total_count'],
            'listings'    => []
        ];
    }

    /**
     * @param $assets
     * @param $listingAssetData
     * @return array
     * @throws InvalidSelectorException
     */
    public static function getAssetData($assets, $listingAssetData): array
    {
        $asset = $assets[$listingAssetData['appid']][$listingAssetData['contextid']][$listingAssetData['id']];

        $assetProperties = self::getAssetProperties($asset);

        return [
            'id' => $asset['id'],
            'class_id' => $asset['classid'],
            'instance_id' => $asset['instanceid'],
            'market_hash_name' => $asset['market_hash_name'],
            'icon_url' => array_key_exists('icon_url', $asset) ? $asset['icon_url'] : '',
            'icon_url_large' => array_key_exists('icon_url_large', $asset) ? $asset['icon_url_large'] : '',
            'asset_properties' => $assetProperties,
            'stickers' => self::parseAccessoryFromDescription($asset, 'sticker'),
            'charms' => self::parseAccessoryFromDescription($asset, 'charm'),
            'patches' => self::parseAccessoryFromDescription($asset, 'patch'),
            'amount' => $asset['amount'],
            'status' => $asset['status'],
            'tradable' => $asset['tradable'],
            'marketable' => $asset['marketable'],
            'inspect_link' => self::getInspectLink($asset, $assetProperties)
        ];
    }

    private static function getAssetProperties(array $asset): array
    {
        $map = [
            1 => ['key' => 'paint_seed',       'field' => 'int_value'],
            2 => ['key' => 'float_value',       'field' => 'float_value'],
            6 => ['key' => 'item_certificate',  'field' => 'string_value'],
        ];

        $properties = array_column($asset['asset_properties'] ?? [], null, 'propertyid');

        $result = [];
        foreach ($map as $id => ['key' => $key, 'field' => $field]) {
            $result[$key] = $properties[$id][$field] ?? null;
        }

        return $result;
    }

    /**
     * @param array $assetProperties
     * @param array $asset
     * @return string
     */
    private static function getInspectLink(array $asset, array $assetProperties): string
    {
        if (empty($assetProperties['item_certificate']) || empty($asset['actions'][0]['link'])) {
            return '';
        }

        return str_replace("%propid:6%", $assetProperties['item_certificate'], $asset['actions'][0]['link']);
    }

    /**
     * @param $asset
     * @param $type
     * @return string
     * @throws InvalidSelectorException
     */
    public static function parseAccessoryFromDescription($asset, $type): string
    {
        if (empty($asset['descriptions']) || !is_array($asset['descriptions']))
            return '';

        $typesMap = [
            'sticker' => [ 'id' => 'sticker_info', 'prefix' => 'Sticker: ' ],
            'charm' => [ 'id' => 'keychain_info', 'prefix' => ['Souvenir Charm: ', 'Charm: '] ],
            'patch' => [ 'id' => 'sticker_info', 'prefix' => 'Patch: ' ]
        ];

        if (!isset($typesMap[$type]))
            return '';

        $conf = $typesMap[$type];

        foreach ($asset['descriptions'] as $desc) {
            if ($desc['name'] !== $conf['id'])
                continue;

            $prefixes = is_array($conf['prefix']) ? $conf['prefix'] : [$conf['prefix']];
            $found = false;

            foreach ($prefixes as $prefix) {
                if (str_contains($desc['value'], $prefix)) {
                    $found = true;
                    break;
                }
            }

            if (!$found)
                continue;

            $doc = new Document($desc['value']);
            $nodeList = $doc->find("#{$conf['id']}");

            if (!empty($nodeList)) {
                $text = $nodeList[0]->text();
                unset($doc);
                return str_replace($prefixes, '', $text);
            }

            unset($doc);
        }

        return '';
    }

    /**
     * @param $data
     * @param $select
     * @param $makeHidden
     * @return array
     */
    public static function filterData($data, $select, $makeHidden): array
    {
        $returnData = self::selectKeys($data, $select);

        self::hideKeys($returnData, $makeHidden);

        return $returnData;
    }

    /**
     * @param $arr
     * @param $keys
     * @return array
     */
    public static function selectKeys($arr, $keys): array
    {
        if (!$keys || !is_array($keys))
            return $arr;

        $saved = [];

        foreach ($keys as $key => $value) {
            if (is_int($key) || is_int($value))
                $keysKey = $value;
            else
                $keysKey = $key;

            $isKeyList = false;
            $isList = false;

            if ((preg_match('/%.+%/', $keysKey, $matches))) {
                $isKeyList = true;
                $keysKey = str_replace('%', '', $matches[0]);
            }

            if ($keysKey === "#list#")
                $isList = true;

            if ($isList) {
                foreach ($arr as $listKey => $listValue)
                    $saved[$listKey] = self::selectKeys($listValue, $value);
            } elseif (isset($arr[$keysKey])) {
                $saved[$keysKey] = $arr[$keysKey];

                if (is_array($value)) {
                    if ($isKeyList)
                        foreach ($arr[$keysKey] as $listKey => $listValue)
                            $saved[$keysKey][$listKey] = self::selectKeys($saved[$keysKey][$listKey], $value);
                    else
                        $saved[$keysKey] = self::selectKeys($saved[$keysKey], $keys[$keysKey]);
                }
            }
        }

        return $saved;
    }

    /**
     * @param $arr
     * @param $keys
     */
    public static function hideKeys(&$arr, $keys)
    {
        if (!$keys || !is_array($keys))
            return;

        foreach ($keys as $key => $value) {
            if (is_int($key) || is_int($value))
                $keysKey = $value;
            else
                $keysKey = $key;

            $isKeyList = false;
            $isList = false;

            if ((preg_match('/%.+%/', $keysKey, $matches))) {
                $isKeyList = true;
                $keysKey = str_replace('%', '', $matches[0]);
            }

            if ($keysKey === "#list#")
                $isList = true;

            if ($isList) {
                foreach ($arr as $listKey => $listValue)
                    self::hideKeys($arr[$listKey], $value);
            } if (isset($arr[$keysKey])) {
                if (is_array($value))
                    if ($isKeyList)
                        foreach ($arr[$keysKey] as $listKey => $listValue)
                            self::hideKeys($arr[$keysKey][$listKey], $value);
                    else
                        self::hideKeys($arr[$keysKey], $keys[$keysKey]);
                else
                    unset($arr[$keysKey]);
            }
        }
    }
}