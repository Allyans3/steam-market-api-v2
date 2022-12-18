<?php

namespace SteamApi\Services;

class TradeOffersRequestService
{
    /**
     * @param $myItems
     * @param $partnerItems
     * @return array
     */
    public static function generateOfferData($myItems, $partnerItems): array
    {
        return [
            'newversion' => true,
            'version' => count($myItems) + count($partnerItems) + 1,
            'me' => [
                'assets' => array_map(function ($item) {
                    return [
                        'appid' => $item['app_id'],
                        'contextid' => $item['context_id'],
                        'amount' => $item['amount'],
                        'assetid' => $item['asset_id'],
                    ];
                }, $myItems),
                'currency' => [],
                'ready' => false
            ],
            'them' => [
                'assets' => array_map(function ($item) {
                    return [
                        'appid' => $item['app_id'],
                        'contextid' => $item['context_id'],
                        'amount' => $item['amount'],
                        'assetid' => $item['asset_id'],
                    ];
                }, $partnerItems),
                'currency' => [],
                'ready' => false
            ]
        ];
    }
}