<?php

namespace SteamApi\Responses\TradeOffers;

use Carbon\Carbon;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use SteamApi\Interfaces\ResponseInterface;
use SteamApi\Services\ResponseService;
use SteamApi\Services\SteamService;

class TradeOffer implements ResponseInterface
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
                    $returnData['response'] = self::completeData($data);

                return $returnData;
            } else {
                if (!$returnData)
                    return false;

                return self::completeData($returnData);
            }
        }
    }

    /**
     * @param $response
     * @return array
     * @throws InvalidSelectorException
     */
    private function completeData($response): array
    {
        $returnData = [
            'status' => false,
            'message' => "",
            'trade_info' => []
        ];

        $document = new Document($response);

        if ($document->has('#error_msg')) {
            $returnData['message'] = trim($document->find('#error_msg')[0]->text());
            return $returnData;
        }

        if (!$document->has('.trade_area'))
            return $returnData;

        $tradeHeaderInfo = self::getTradeHeaderInfo($document);
        $steamId = self::cutTool($response, "var g_ulTradePartnerSteamID = '", "';");

        $returnData['status'] = true;
        $returnData['trade_info'] = ResponseService::filterData([
            'message' => self::getMessage($document),
            'sender_info' => [
                'account_id' => SteamService::toPartnerId($steamId),
                'steam_id' => $steamId,
                'name' => self::cutTool($response, 'var g_strTradePartnerPersonaName = "', '";'),
                'probation' => (bool) self::cutTool($response, "var g_bTradePartnerProbation = ", ";"),
                'level' => $tradeHeaderInfo['level'],
                'registration_date' => $tradeHeaderInfo['registration_date'],
                'old_names' => $tradeHeaderInfo['old_names']
            ],
            'trade_status' => self::parseTradeStatus($response),
            'wallet_info' => self::parseWalletInfo($response)
        ], $this->select, $this->makeHidden);

        return $returnData;
    }

    /**
     * @param $document
     * @return mixed
     */
    private function getMessage($document)
    {
        $message = $document->find('.included_trade_offer_note_ctn .quote');

        if ($message)
            return $message[0]->text();

        return "";
    }

    /**
     * @param $str
     * @param $delimiterStart
     * @param $delimiterEnd
     * @return false|string
     */
    private function cutTool($str, $delimiterStart, $delimiterEnd)
    {
        $dataString = substr($str, strpos($str, $delimiterStart) + strlen($delimiterStart));
        return substr($dataString, 0, strpos($dataString, $delimiterEnd));
    }

    /**
     * @param $document
     * @return array
     */
    private function getTradeHeaderInfo($document): array
    {
        $header = $document->find('.trade_partner_header')[0];

        return [
            'level' => (int) $header->find('.friendPlayerLevelNum')[0]->text(),
            'registration_date' => Carbon::parse($header->find('.trade_partner_member_since')[0]->text())->timestamp,
            'old_names' => self::getOldNames($header)
        ];
    }

    /**
     * @param $header
     * @return false|string[]
     */
    private function getOldNames($header)
    {
        $oldNamesAttr = $header->find('.trade_partner_headline .trade_partner_recently_changed_name a')[0]
            ->attr('data-tooltip-html');

        $oldNames = explode('<br>', $oldNamesAttr);

        unset($oldNames[0]);

        return $oldNames;
    }

    /**
     * @param $str
     * @return mixed
     */
    private function parseTradeStatus($str)
    {
        $delimiterStart = "var g_rgCurrentTradeStatus = ";
        $delimiterEnd = ";";

        $dataString = substr($str, strpos($str, $delimiterStart) + strlen($delimiterStart));
        $dataString = substr($dataString, 0, strpos($dataString, $delimiterEnd));

        return json_decode($dataString, true);
    }

    /**
     * @param $str
     * @return mixed
     */
    private function parseWalletInfo($str)
    {
        $delimiterStart = "var g_rgWalletInfo = ";
        $delimiterEnd = ";";

        $dataString = substr($str, strpos($str, $delimiterStart) + strlen($delimiterStart));
        $dataString = substr($dataString, 0, strpos($dataString, $delimiterEnd));

        return json_decode($dataString, true);
    }
}