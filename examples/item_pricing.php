<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\Configs\Apps;
use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
    'market_hash_name' => "AK-47 | Slate (Field-Tested)",
    'country' => 'US',                                    //optional
    'currency' => 1                                       //optional
];

dd($api->detailed()->getItemPricing(Apps::CSGO_ID, $options));





// Example response:
//
//"response" => array:5 [▼
//    "volume" => 1899
//    "lowest_price" => 2.65
//    "lowest_price_str" => "$2.65"
//    "median_price" => 2.6
//    "median_price_str" => "$2.65"
//]