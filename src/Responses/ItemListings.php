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
        $data = json_decode($response, true);

        if (!$data) {
            return false;
        }

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