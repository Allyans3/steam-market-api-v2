<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\Configs\Apps;
use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
    'market_hash_name' => "AK-47 | Slate (Field-Tested)"
];

dd($api->detailed()->getSaleHistory(Apps::CSGO_ID, $options));





// Example response:
//
//"response" => array:1285 [▼
//    0 => array:3 [▼
//        "time" => 1620000000
//        "price" => 71.358
//        "volume" => 20
//    ]
//    1 => array:3 [▶]
//    ...
//]