<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\Configs\Apps;
use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
    'market_hash_name' => "Recoil Case",
    'item_name_id' => 176321160,
    'country' => 'US',                   //optional
    'language' => 'english',             //optional
    'currency' => 1,                     //optional
    'two_factor' => 0                    //optional
];

dd($api->detailed()->getItemOrdersActivity(Apps::CSGO_ID, $options));





// Example response:
//
//"response" => array:3 [▼
//    "success" => true
//    "activity" => array:8 [▼
//        0 => array:11 [▼
//            "type" => "SellOrder"
//            "quantity" => "1"
//            "price" => 0.65
//            "time" => 1669499361
//            "avatar_buyer" => null
//            "avatar_medium_buyer" => null
//            "persona_buyer" => null
//            "avatar_seller" => "https://avatars.cloudflare.steamstatic.com/c01e01e1f77f3eea5123b6e16632400fd0b375cc.jpg"
//            "avatar_medium_seller" => "https://avatars.cloudflare.steamstatic.com/c01e01e1f77f3eea5123b6e16632400fd0b375cc_medium.jpg"
//            "persona_seller" => "Deadmos"
//            "price_str" => "$0.65"
//        ]
//        1 => array:11 [▶]
//        2 => array:11 [▶]
//        3 => array:11 [▶]
//        4 => array:11 [▶]
//        5 => array:11 [▶]
//        6 => array:11 [▶]
//        7 => array:11 [▶]
//    ]
//    "timestamp" => 1669499361
//]