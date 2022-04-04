<?php

namespace SteamApi\Responses;

use DiDom\Document;
use SteamApi\Interfaces\ResponseInterface;
use SteamApi\Mixins\Mixins;

class ItemListings implements ResponseInterface
{
    private $data;

    public function __construct($response)
    {
        $this->data = $this->decodeResponse($response);
    }

    public function response()
    {
        return $this->data;
    }

    private function decodeResponse($response)
    {
        if (is_array($response) && array_key_exists('multi_list', $response)) {
            $items = [];

            foreach ($response['multi_list'] as $list) {
                $parsedItems = $this->parseItems($list);

                $items = array_merge($items, $parsedItems['items']);
            }

            if (!$items)
                return false;

            $tempArr = array_unique(array_column($items, 'listingId'));

            return array_intersect_key($items, $tempArr);
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

    private function parseItems($data): array
    {
        $returnData = Mixins::fillBaseData($data);

        $document = new Document($data['results_html']);
        $rawNode = $document->find('.market_listing_row, market_recent_listing_row');

        foreach ($rawNode as $node) {
            $item = $this->parseNode($node);

            foreach ($data['listinginfo'] as $key => $value) {
                if ($key == $item['listingId']) {
                    $item['inspectLink'] = Mixins::generateInspectLink($value);
                    break;
                }
            }

            $returnData['items'][] = $item;
        }

        return $returnData;
    }

    private function parseNode($node): array
    {
        $price_with_fee = trim($node->find('.market_listing_price_with_fee')[0]->text());
        $price_with_publisher_fee_only = trim($node->find('.market_listing_price_with_publisher_fee_only')[0]->text());
        $price_without_fee = trim($node->find('.market_listing_price_without_fee')[0]->text());

        $image = $node->find('img')[0]->attr('src');
        $imageLarge = str_replace('/62fx62f', '', $image);

        return [
            'listingId' => substr($node->attr('id'), 8),
            'name' => $node->find('.market_listing_item_name')[0]->text(),
            'image' => $image,
            'imageLarge' => $imageLarge,

            'price_with_fee' => Mixins::toFloat($price_with_fee),
            'price_with_fee_str' => $price_with_fee,

            'price_with_publisher_fee_only' => Mixins::toFloat($price_with_publisher_fee_only),
            'price_with_publisher_fee_only_str' => $price_with_publisher_fee_only,

            'price_without_fee' => Mixins::toFloat($price_without_fee),
            'price_without_fee_str' => $price_without_fee
        ];
    }
}