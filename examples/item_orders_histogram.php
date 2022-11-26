<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\Configs\Apps;
use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
    'market_hash_name' => "AK-47 | Slate (Field-Tested)",
    'item_name_id' => 176241017,
    'country' => 'US',                                    //optional
    'language' => 'english',                              //optional
    'currency' => 1,                                      //optional
    'two_factor' => 0                                     //optional
];

dd($api->detailed()->getItemOrdersHistogram(Apps::CSGO_ID, $options));





// Example response:
//
//"response" => array:13 [▼
//    "highest_buy_order" => 2.55
//    "lowest_sell_order" => 2.57
//    "buy_order_summary" => 51893
//    "sell_order_summary" => 2763
//    "buy_order_graph" => array:101 [▼
//        0 => array:3 [▼
//            "price" => 2.55
//            "volume" => 5
//            "description" => "5 buy orders at $2.55 or higher"
//        ]
//        1 => array:3 [▶]
//        ...
//    ]
//    "sell_order_graph" => array:101 [▼
//        0 => array:3 [▼
//            "price" => 2.57
//            "volume" => 1
//            "description" => "1 sell orders at $2.57 or lower"
//        ]
//        1 => array:3 [▶]
//        ...
//    ]
//    "buy_order_table" => array:6 [▼
//        0 => array:3 [▼
//            "price" => 2.55
//            "price_text" => "$2.55"
//            "count" => 5
//        ]
//        1 => array:3 [▶]
//        ...
//    ]
//    "sell_order_table" => array:6 [▼
//        0 => array:3 [▼
//            "price" => 2.57
//            "price_text" => "$2.57"
//            "count" => 1
//        ]
//        1 => array:3 [▶]
//        ...
//    ]
//    "graph_max_y" => 30000
//    "graph_min_x" => 1.34
//    "graph_max_x" => 3.96
//    "price_prefix" => "$"
//    "price_suffix" => ""
//]