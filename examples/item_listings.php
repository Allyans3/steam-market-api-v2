<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\Configs\Apps;
use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
    'market_hash_name' => "AK-47 | Slate (Field-Tested)",
    'query' => "",                                         //optional
    'start' => 0,                                          //optional
    'count' => 10,                                         //optional
    'country' => 'US',                                     //optional
    'language' => 'english',                               //optional
    'currency' => 1,                                       //optional
    'filter' => ""                                         //optional e.g. sticker, charm, patch
];

dd($api->detailed()->getItemListings(Apps::CSGO_ID, $options));





// Example response:
//
//"response" => array:4 [▼
//    "start" => 0
//    "page_size" => 10
//    "total_count" => 1657
//    "listings" => array:10 [▼
//        0 => array:4 [▼
//        "listing_id" => "627827340810442306"
//        "asset" => array:15 [▼
//            "id" => "48105683752"
//            "class_id" => "7993035629"
//            "instance_id" => "6452843066"
//            "market_hash_name" => "AK-47 | Slate (Field-Tested)"
//            "icon_url" => "i0CoZ81Ui0m-9KwlBY1L_18myuGuq1wfhWSaZgMttyVfPaERSR0Wqmu7LAocGIGz3UqlXOLrxM-vMGmW8VNxu5Dx60noTyLwlcK3wiVI0POlPPNSMOKcCGKD0ud5vuBlcCS2kRQyvnOGw4r_d3OWZ1MnCpBwR-Rc ▶"
//            "icon_url_large" => ""
//            "asset_properties" => array:3 [▼
//                "paint_seed" => "386"
//                "float_value" => "0.291784584522247314"
//                "item_certificate" => "C6D66E300F5C75C7DEC1E64DCEEEC2F6C2FE0C0F1332C58644C5A4C3CEC6D626EEA4C3CEC7D626EEA4C3CEC4D626EEA4C3CEC5D629D4AEC1B6CEFF3AA4E6"
//            ]
//            "stickers" => "FL1T | Stockholm 2021, FL1T | Stockholm 2021, FL1T | Stockholm 2021, Green Swallow"
//            "charms" => ""
//            "patches" => ""
//            "amount" => "1"
//            "status" => 10
//            "tradable" => 1
//            "marketable" => 1
//            "inspect_link" => "steam://run/730//+csgo_econ_action_preview%20C6D66E300F5C75C7DEC1E64DCEEEC2F6C2FE0C0F1332C58644C5A4C3CEC6D626EEA4C3CEC7D626EEA4C3CEC4D626EEA4C3CEC5D629D4AEC1B6C ▶"
//        ]
//        "original_price_data" => array:5 [▼
//            "currency_id" => 5
//            "currency" => "RUB"
//            "price_with_fee" => 640.98
//            "price_with_publisher_fee_only" => 613.12
//            "price_without_fee" => 557.39
//        ]
//        "price_data" => array:5 [▼
//            "currency_id" => 1
//            "currency" => "USD"
//            "price_with_fee" => 8.06
//            "price_with_publisher_fee_only" => 7.71
//            "price_without_fee" => 7.01
//        ]
//        ]
//        1 => array:4 [▶]
//        2 => array:4 [▶]
//        3 => array:4 [▶]
//        4 => array:4 [▶]
//        5 => array:4 [▶]
//        6 => array:4 [▶]
//        7 => array:4 [▶]
//        8 => array:4 [▶]
//        9 => array:4 [▶]
//    ]
//]