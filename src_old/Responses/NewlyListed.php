<?php

namespace SteamApiOld\Responses;

use SteamApiOld\Interfaces\ResponseInterface;
use SteamApiOld\Mixins\Mixins;

class NewlyListed implements ResponseInterface
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

    public function __destruct()
    {
        unset($this->data);
    }

    private function decodeResponse($response)
    {
        if (!is_array($response)) {
            $data = json_decode($response, true);

            if (!$data)
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
        $skins = [];

        foreach ($data['listinginfo'] as $listing) {
            if ($listing['publisher_fee_app'] === 730) {
                if (array_key_exists('730', $data['assets'])) {
                    foreach ($data['assets']['730']['2'] as $asset) {
                        if ($asset['id'] === $listing['asset']['id'])
                            $skins[] = $this->completeData($listing, $asset);
                    }
                }
            }
        }

        return $skins;
    }

    private function completeData($listingData, $assetData): array
    {
        $image = "https://steamcommunity-a.akamaihd.net/economy/image/" . $assetData['icon_url'];
        $imageLarge = array_key_exists('icon_url_large', $assetData) ?
            "https://steamcommunity-a.akamaihd.net/economy/image/" . $assetData['icon_url_large'] : "";

        if ($assetData['status'] === 2) {
            $price_without_fee = $listingData['converted_price_per_unit'] / 100;
            $publisher_fee = $listingData['converted_publisher_fee'] / 100;
            $steam_fee = $listingData['converted_steam_fee'] / 100;
            $price_with_fee = $price_without_fee + ($listingData['converted_fee_per_unit'] / 100);
        } else
            $price_without_fee = $publisher_fee = $steam_fee = $price_with_fee = 0;


        $inspectData = Mixins::createNewlyListedInspectLink($assetData);

        return [
            'listing_id' => $listingData['listingid'],
            'name' => $assetData['market_hash_name'],
            'image' => $image,
            'image_large' => $imageLarge,
            'inspectable' => $inspectData['inspectable'],
            'inspect_link' => $inspectData['inspectLink'],
            'stickers' => Mixins::parseStickers($assetData['descriptions']),
            'type' => $assetData['type'],

            'status' => $assetData['status'],

            'price_with_fee' => $price_with_fee,
            'publisher_fee' => $publisher_fee,
            'steam_fee' => $steam_fee,
            'price_without_fee' => $price_without_fee,
        ];
    }
}