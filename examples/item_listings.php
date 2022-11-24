<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\Configs\Apps;
use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
    'market_hash_name' => "AK-47 | Slate (Field-Tested)",
//    'query' => "",                                         //optional
//    'start' => 0,                                          //optional
//    'count' => 10,                                         //optional
//    'country' => 'US',                                     //optional
//    'language' => 'english',                               //optional
//    'currency' => 1,                                       //optional
//    'filter' => ""                                         //optional
];

dd($api->detailed()->getItemListings(Apps::CSGO_ID, $options));