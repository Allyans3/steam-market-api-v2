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

        return [
            'id' => $asset['id'],
            'class_id' => $asset['classid'],
            'instance_id' => $asset['instanceid'],
            'market_hash_name' => $asset['market_hash_name'],
            'icon_url' => $asset['icon_url'],
            'icon_url_large' => $asset['icon_url_large'],
            'stickers' => self::parseStickersFromDescription($asset['descriptions']),
            'amount' => $asset['amount'],
            'status' => $asset['status'],
            'tradable' => $asset['tradable'],
            'marketable' => $asset['marketable'],
            'inspect_link' => str_replace("%assetid%", $asset['id'], $asset['actions'][0]['link'])
        ];
    }

    /**
     * @param $description
     * @return array|string|string[]
     * @throws InvalidSelectorException
     */
    public static function parseStickersFromDescription($description)
    {
        $stickers = '';

        foreach ($description as $value) {
            if (str_contains($value['value'], 'Sticker: ')) {
                $document = new Document($value['value']);
                $listingsNode = $document->find('#sticker_info')[0]->text();

                $stickers = str_replace('Sticker: ', '', $listingsNode);

                unset($document);
            }
        }

        return $stickers;
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
        if (!$keys)
            return $arr;

        $saved = [];

        foreach ($keys as $key => $value) {
            if (is_int($key) || is_int($value))
                $keysKey = $value;
            else
                $keysKey = $key;

            $isList = false;

            if ((preg_match('/%.+%/', $keysKey, $matches))) {
                $isList = true;
                $keysKey = str_replace('%', '', $matches[0]);
            }

            if (isset($arr[$keysKey])) {
                $saved[$keysKey] = $arr[$keysKey];

                if (is_array($value)) {
                    if ($isList)
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
        if (!$keys)
            return;

        foreach ($keys as $key => $value) {
            if (is_int($key) || is_int($value))
                $keysKey = $value;
            else
                $keysKey = $key;

            $isList = false;

            if ((preg_match('/%.+%/', $keysKey, $matches))) {
                $isList = true;
                $keysKey = str_replace('%', '', $matches[0]);
            }

            if (isset($arr[$keysKey])) {
                if (is_array($value))
                    if ($isList)
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