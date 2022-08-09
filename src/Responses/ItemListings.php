<?php

namespace SteamApi\Responses;

use DiDom\Document;
use Exception;
use SteamApi\Interfaces\ResponseInterface;
use SteamApi\Mixins\Mixins;

class ItemListings implements ResponseInterface
{
    private $data;

    public function __construct($response)
    {
        $this->data = $this->decodeResponse($response);
    }

    public function __destruct()
    {
        unset($this->data);
    }

    public function response()
    {
        return $this->data;
    }

    private function decodeResponse($response)
    {
        if (is_array($response) && array_key_exists('multi_list', $response)) {
            $items = [];
            $baseData = [];

            foreach ($response['multi_list'] as $list) {
                $parsedItems = $this->parseItems($list);

                $baseData = [
                    'start' => $parsedItems['start'],
                    'pagesize' => $parsedItems['pagesize'],
                    'total_count' => $parsedItems['total_count'],
                ];
                $items = array_merge($items, $parsedItems['items']);
            }

            if (!$items)
                return false;

            $tempArr = array_unique(array_column($items, 'listingId'));

            $baseData['items'] = array_intersect_key($items, $tempArr);

            return $baseData;
        } else if (!is_array($response)) {
            $data = json_decode($response, true);

            if (!$data && !is_array($data))
                return false;

            return $this->parseItems($data);
        } else {
            $returnData = $response;

            $data = json_decode($response['response'], true);

            if (!$data) {
                $returnData['response'] = false;
                return $returnData;
            }

            $returnData['response'] = $this->parseItems($data);

            return $returnData;
        }
    }

    /**
     * @throws Exception
     */
    private function parseItems($data): array
    {
        $returnData = Mixins::fillBaseData($data);

        $document = new Document($data['results_html']);
        $rawNode = $document->find('.market_listing_row, market_recent_listing_row');

        foreach ($rawNode as $node) {
            $item = $this->parseNode($node);

            foreach ($data['listinginfo'] as $listingId => $value) {
                if ($listingId == $item['listingId']) {
                    if ($value['price'] > 0 && $value['fee'] > 0) {
                        $item['price_with_fee'] = ($value['converted_price'] + $value['converted_fee']) / 100;
                        $item['price_with_publisher_fee_only'] = ($value['converted_price'] + $value['converted_publisher_fee']) / 100;
                        $item['price_without_fee'] = $value['converted_price'] / 100;
                    }
                    $item['inspectLink'] = Mixins::generateInspectLink($value);
                }
            }

            $returnData['items'][] = $item;
        }

        unset($document);

        return $returnData;
    }

    private function parseNode($node): array
    {
        $image = $node->find('img')[0]->attr('src');
        $imageLarge = str_replace('/62fx62f', '', $image);

        return [
            'listingId' => substr($node->attr('id'), 8),
            'name' => $node->find('.market_listing_item_name')[0]->text(),
            'image' => $image,
            'imageLarge' => $imageLarge,

            'price_with_fee' => 0,
            'price_with_fee_str' => trim($node->find('.market_listing_price_with_fee')[0]->text()),

            'price_with_publisher_fee_only' => 0,
            'price_with_publisher_fee_only_str' => trim($node->find('.market_listing_price_with_publisher_fee_only')[0]->text()),

            'price_without_fee' => 0,
            'price_without_fee_str' => trim($node->find('.market_listing_price_without_fee')[0]->text())
        ];
    }
}