<?php

namespace SteamApi\Responses\MobileConfirmations;

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use SteamApi\Interfaces\ResponseInterface;
use SteamApi\Services\ResponseService;

class ConfListV2 implements ResponseInterface
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
                    $returnData['response'] = self::parseConfirmations($data);

                return $returnData;
            } else {
                if (!$returnData)
                    return false;

                return self::parseConfirmations($returnData);
            }
        }
    }

    /**
     * @param $data
     * @return array
     * @throws InvalidSelectorException
     */
    private function parseConfirmations($data): array
    {
        $returnData = [
            'status' => false,
            'messages' => [],
            'confirmations' => []
        ];

        $document = new Document($data);

        if ($document->has('#mobileconf_empty')) {
            $text = $document->find('#mobileconf_empty')[0]->text();
            $returnData['messages'] = array_values(self::textToArray($text));

            if (array_key_exists(0, $returnData['messages']) &&
                $returnData['messages'][0] === "Nothing to confirm")
                $returnData['status'] = true;

            return $returnData;
        }

        $confList = $document->find('.mobileconf_list_entry');

        if ($confList)
            $returnData['status'] = true;

        foreach ($confList as $confNode)
            $returnData['confirmations'][] = self::completeData($confNode);

        return ResponseService::filterData($returnData, $this->select, $this->makeHidden);
    }

    /**
     * @param $confNode
     * @return array
     */
    private function completeData($confNode): array
    {
        $image = $confNode->find('.mobileconf_list_entry_icon img');

        return [
            'id' => $confNode->attr('data-confid'),
            'key' => $confNode->attr('data-key'),
            'type' => $confNode->attr('data-type'),
            'trade_id' => $confNode->attr('data-creator'),
            'title' => $confNode->find('.mobileconf_list_entry_description>div:nth-of-type(1)')[0]->text(),
            'receiving' => $confNode->find('.mobileconf_list_entry_description>div:nth-of-type(2)')[0]->text(),
            'time' => $confNode->find('.mobileconf_list_entry_description>div:nth-of-type(3)')[0]->text(),
            'image' => $image ? $image[0]->attr('src') : ''
        ];
    }

    /**
     * @param $text
     * @return array|false|string[]
     */
    private function textToArray($text)
    {
        $textArr = preg_split("/\r\n|\n|\r/", $text);

        foreach ($textArr as &$str)
            $str = trim($str);

        return array_filter($textArr, fn($value) => !is_null($value) && $value !== '');
    }
}