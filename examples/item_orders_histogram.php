<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\Configs\Apps;
use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
    'market_hash_name' => "AK-47 | Slate (Field-Tested)",
    'item_name_id' => 176241017,
//    'country' => 'US',                                    //optional
//    'language' => 'english',                              //optional
//    'currency' => 1,                                      //optional
//    'two_factor' => 0                                     //optional
];

dd($api->detailed()->getItemOrdersHistogram(Apps::CSGO_ID, $options));