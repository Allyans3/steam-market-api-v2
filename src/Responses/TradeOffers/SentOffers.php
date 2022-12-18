<?php

namespace SteamApi\Responses\TradeOffers;

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use SteamApi\Interfaces\ResponseInterface;
use SteamApi\Services\ResponseService;
use SteamApi\Services\TradeOffersResponseService;

class SentOffers implements ResponseInterface
{
    private $response;
    private $detailed;
    private $multiRequest;

    private $select;
    private $makeHidden;

    /**
     * @param $response
     * @param bool $detailed
     * @param bool $multiRequest
     */
    public function __construct($response, bool $detailed = false, bool $multiRequest = false)
    {
        $this->response = $response;
        $this->detailed = $detailed;
        $this->multiRequest = $multiRequest;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->response);
        unset($this->detailed);
        unset($this->multiRequest);

        unset($this->select);
        unset($this->makeHidden);
    }

    /**
     * @param array $select
     * @param array $makeHidden
     * @return array|false
     * @throws InvalidSelectorException
     */
    public function response(array $select = [], array $makeHidden = [])
    {
        $this->select = $select;
        $this->makeHidden = $makeHidden;

        return $this->decodeResponse($this->response);
    }

    /**
     * @param $response
     * @return array|false
     * @throws InvalidSelectorException
     */
    public function decodeResponse($response)
    {
        if ($this->multiRequest) {
            // TODO
            return false;
        } else {
            $returnData = $response;

            if ($this->detailed) {
                $data = $returnData['response'];

                if (!$data)
                    $returnData['response'] = false;
                else
                    $returnData['response'] = self::parseOffers($data);

                return $returnData;
            } else {
                if (!$returnData)
                    return false;

                return self::parseOffers($returnData);
            }
        }
    }

    /**
     * @param $data
     * @return array
     * @throws InvalidSelectorException
     */
    private function parseOffers($data): array
    {
        $returnData = [];

        $document = new Document($data);
        $tradeOffers = $document->find('.tradeoffer');

        foreach ($tradeOffers as $tradeOffer) {
            $returnData[] = self::parseTradeOfferHTML($tradeOffer);
        }

        unset($document);

        return ResponseService::filterData($returnData, $this->select, $this->makeHidden);
    }

    /**
     * @param $tradeOffer
     * @return array
     */
    private function parseTradeOfferHTML($tradeOffer): array
    {
        $bannerMsg = TradeOffersResponseService::getBannerMessage($tradeOffer);

        return [
            'status' => !$bannerMsg,
            'id' => str_replace('tradeofferid_', '', $tradeOffer->attr('id')),
            'banner_message' => $bannerMsg,
            'message' => TradeOffersResponseService::parseMessage($tradeOffer),
            'is_our_offer' => true,
            'expiration_time' => TradeOffersResponseService::parseExpiredTime($tradeOffer->find('.tradeoffer_footer')[0]->text()),
            'trade_offer_state' => TradeOffersResponseService::getTradeOfferStateId($bannerMsg),

            'receiver_info' => [
                'account_id' => (int) $tradeOffer->find('.tradeoffer_items.secondary .tradeoffer_avatar')[0]->attr('data-miniprofile'),
                'name' => self::parseReceiverName($tradeOffer->find('.tradeoffer_items.secondary .tradeoffer_items_header')[0]->text()),
                'link' => $tradeOffer->find('.tradeoffer_items.secondary .tradeoffer_avatar')[0]->attr('href'),
                'avatar' => $tradeOffer->find('.tradeoffer_items.secondary .tradeoffer_avatar img')[0]->attr('src'),
                'status' => str_replace('tradeoffer_avatar playerAvatar tiny ', '',
                    $tradeOffer->find('.tradeoffer_items.secondary .tradeoffer_avatar')[0]->attr('class'))
            ],

            'items_to_receive' => TradeOffersResponseService::parseItems($tradeOffer->find('.tradeoffer_items.primary .tradeoffer_item_list .trade_item')),
            'items_to_give' => TradeOffersResponseService::parseItems($tradeOffer->find('.tradeoffer_items.secondary .tradeoffer_item_list .trade_item')),
        ];
    }

    /**
     * @param $str
     * @return false|string
     */
    private function parseReceiverName($str)
    {
        $delimiterStart = "For ";
        $delimiterEnd = "'s:";

        $dataString = substr($str, strpos($str, $delimiterStart) + strlen($delimiterStart));
        return substr($dataString, 0, strpos($dataString, $delimiterEnd));
    }
}