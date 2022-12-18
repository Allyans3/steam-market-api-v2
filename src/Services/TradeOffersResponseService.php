<?php

namespace SteamApi\Services;

use Carbon\Carbon;
use SteamApi\Configs\TradeOffersConfig;

class TradeOffersResponseService
{
    /**
     * @param $items
     * @return array
     */
    public static function parseItems($items): array
    {
        $returnData = [];

        foreach ($items as $item) {
            $exploded = explode('/', $item->attr('data-economy-item'));

            $returnData[] = [
                "app_id" => $exploded[1],
                "class_id" => array_key_exists(2, $exploded) ? $exploded[2] : 0,
                "instance_id" => array_key_exists(3, $exploded) ? $exploded[3] : 0,
                "amount" => 1
            ];
        }

        return $returnData;
    }

    /**
     * @param $bannerMsg
     * @return int
     */
    public static function getTradeOfferStateId($bannerMsg): int
    {
        foreach (array_reverse(TradeOffersConfig::STATE, true) as $key => $msg) {
            if (str_contains($bannerMsg, $msg))
                return $key;
        }

        return 0;
    }

    /**
     * @param $tradeOffer
     * @return string
     */
    public static function getBannerMessage($tradeOffer): string
    {
        $tradeOfferMsg = $tradeOffer->find('.tradeoffer_items_banner');
        $bannerMsg = "";

        if ($tradeOfferMsg)
            $bannerMsg = trim($tradeOfferMsg[0]->text());

        return $bannerMsg;
    }

    /**
     * @param $str
     * @return float|int|string
     */
    public static function parseExpiredTime($str)
    {
        $delimiterStart = "Offer expires on";

        if (!str_contains($str, $delimiterStart))
            return "";

        $dataString = substr($str, strpos($str, $delimiterStart) + strlen($delimiterStart));

        return Carbon::parse(trim($dataString))->timestamp;
    }

    /**
     * @param $str
     * @return mixed
     */
    public static function parseSenderSteamId($str)
    {
        $delimiterStart = "ReportTradeScam( '";
        $delimiterEnd = "', ";

        $dataString = substr($str, strpos($str, $delimiterStart) + strlen($delimiterStart));
        $dataString = substr($dataString, 0, strpos($dataString, $delimiterEnd));

        return json_decode($dataString, true);
    }

    /**
     * @param $tradeOffer
     * @return string
     */
    public static function parseMessage($tradeOffer): string
    {
        $message = "";

        if ($tradeOffer->has('.tradeoffer_message')) {
            $message = $tradeOffer->find('.tradeoffer_message .quote')[0]->text();
            $message = str_replace('(?)', '', $message);
            $message = trim(html_entity_decode($message), " \t\n\r\0\x0B\xC2\xA0");
        }

        return $message;
    }
}