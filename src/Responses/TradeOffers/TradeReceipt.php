<?php

namespace SteamApi\Responses\TradeOffers;

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use SteamApi\Interfaces\ResponseInterface;
use SteamApi\Services\ResponseService;

class TradeReceipt implements ResponseInterface
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
     */
    public function response(array $select = [], array $makeHidden = [])
    {
        $this->select = $select;
        $this->makeHidden = $makeHidden;

        return $this->decodeResponse($this->response);
    }

    public function decodeResponse($response)
    {
//        dd($response);
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
                    $returnData['response'] = self::parseItems($data);

                return $returnData;
            } else {
                if (!$returnData)
                    return false;

                return self::parseItems($returnData);
            }
        }
    }

    /**
     * @param $data
     * @return array
     * @throws InvalidSelectorException
     */
    private function parseItems($data): array
    {
        $returnData = [
            'status' => false,
            'message' => "",
            'items' => []
        ];

        $decoded = json_decode($data, true);

        if ($decoded && is_array($decoded) && !$decoded['success'])
            return $returnData;

        $getErrorMsg = self::parseErrorMessage($data);

        if ($getErrorMsg) {
            $returnData['message'] = $getErrorMsg;
            return $returnData;
        }

        $returnData['status'] = true;

        preg_match_all('/oItem = {.*?};/', $data, $matches);

        if (!$matches)
            return $returnData;

        foreach ($matches[0] as $value) {
            $item = rtrim(str_replace('oItem = ', '', $value),';');

            $returnData['items'][] = json_decode($item, true);
        }

        return ResponseService::filterData($returnData, $this->select, $this->makeHidden);
    }

    /**
     * @param $data
     * @return string
     * @throws InvalidSelectorException
     */
    private function parseErrorMessage($data): string
    {
        $document = new Document($data);

        if ($document->has('#error_msg'))
            return trim($document->find('#error_msg')[0]->text());

        return "";
    }
}