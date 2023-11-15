<?php

namespace SteamApi\Responses\Steam;

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use SteamApi\Configs\Economic;
use SteamApi\Services\ResponseService;
use SteamApi\Interfaces\ResponseInterface;

class ItemListings implements ResponseInterface
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
                $data = json_decode($returnData['response'], true);

                if (!$data)
                    $returnData['response'] = false;
                else
                    $returnData['response'] = self::parseListings($data);

                return $returnData;
            } else {
                $data = json_decode($returnData, true);

                if (!$data)
                    return false;

                return self::parseListings($data);
            }
        }
    }

    /**
     * @param $data
     * @return array
     * @throws InvalidSelectorException
     */
    private function parseListings($data): array
    {
        $returnData = ResponseService::fillBaseData($data);

        foreach ($data['listinginfo'] as $listingId => $value) {
            $returnData['listings'][] = self::completeListing($data, $listingId, $value);
        }

        return ResponseService::filterData($returnData, $this->select, $this->makeHidden);
    }

    /**
     * @param $data
     * @param $listingId
     * @param $value
     * @return array
     * @throws InvalidSelectorException
     */
    private function completeListing($data, $listingId, $value): array
    {
        $listingData = [];

        $listingData['listing_id'] = $listingId;
        $listingData['asset'] = ResponseService::getAssetData($data['assets'], $value['asset']);

        if ($value['price'] > 0 && $value['fee'] > 0) {
            $currencyId = $value['currencyid'] - 2000;
            $convertedCurrencyId = $value['converted_currencyid'] - 2000;

            $listingData['original_price_data'] = [
                'currency_id' => $currencyId,
                'currency' => Economic::CURRENCY_LIST[$currencyId],
                'price_with_fee' => ($value['price'] + $value['fee']) / 100,
                'price_with_publisher_fee_only' => ($value['price'] + $value['publisher_fee']) / 100,
                'price_without_fee' => $value['price'] / 100
            ];

            $listingData['price_data']['currency_id'] = $convertedCurrencyId;
            $listingData['price_data']['currency'] = Economic::CURRENCY_LIST[$convertedCurrencyId];
            $listingData['price_data']['price_with_fee'] = ($value['converted_price'] + $value['converted_fee']) / 100;
            $listingData['price_data']['price_with_publisher_fee_only'] = ($value['converted_price'] + $value['converted_publisher_fee']) / 100;
            $listingData['price_data']['price_without_fee'] = $value['converted_price'] / 100;
        }
        return $listingData;
    }
}