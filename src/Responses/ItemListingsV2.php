<?php

namespace SteamApi\Responses;

use DiDom\Document;
use Exception;
use SteamApi\Interfaces\ResponseInterface;

class ItemListingsV2 implements ResponseInterface
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
        return $this->parseItems($response);
    }

    /**
     * @throws Exception
     */
    private function parseItems($data): array
    {
        $returnData = [];

        $document = new Document($data);
        $rawNode = $document->find('.market_listing_row.market_recent_listing_row');

        foreach ($rawNode as $node) {
            $item = $this->parseNode($node);

            $returnData['items'][] = $item;
        }

        unset($document);

        return $returnData;
    }

    private function parseNode($node): array
    {
        return [
            'listingId' => substr($node->attr('id'), 8),
            'assetId' => "",

            'name' => $node->find('.market_listing_item_name')[0]->text(),

            'price_with_fee' => 0,
            'price_with_fee_str' => trim($node->find('.market_listing_price_with_fee')[0]->text()),

            'price_with_publisher_fee_only' => 0,
            'price_with_publisher_fee_only_str' => trim($node->find('.market_listing_price_with_publisher_fee_only')[0]->text()),

            'price_without_fee' => 0,
            'price_without_fee_str' => trim($node->find('.market_listing_price_without_fee')[0]->text())
        ];
    }
}