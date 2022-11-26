<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\SteamApi;


$api = new SteamApi();

dd($api->detailed()->getRecentlySold());





// Example response:
//
//"response" => array:11 [▼
//    "success" => true
//    "more" => true
//    "results_html" => false
//    "listinginfo" => array:10 [▼
//        4066216491414444585 => array:7 [▼
//            "listingid" => "4066216491414444585"
//            "price" => 0
//            "fee" => 0
//            "publisher_fee_app" => 730
//            "publisher_fee_percent" => "0.100000001490116119"
//            "currencyid" => 2005
//            "asset" => array:5 [▼
//                "currency" => 0
//                "appid" => 730
//                "contextid" => "2"
//                "id" => "27146715153"
//                "amount" => "0"
//            ]
//        ]
//        4068468291222989647 => array:7 [▶]
//        ...
//    ]
//    "purchaseinfo" => array:10 [▶]
//    "assets" => array:2 [▼
//        730 => array:1 [▼
//            2 => array:7 [▼
//                27146715153 => array:26 [▼
//                    "currency" => 0
//                    "appid" => 730
//                    "contextid" => "2"
//                    "id" => "27146715153"
//                    "classid" => "4418618853"
//                    "instanceid" => "0"
//                    "amount" => "0"
//                    "status" => 4
//                    "original_amount" => "1"
//                    "unowned_id" => "27146715153"
//                    "unowned_contextid" => "2"
//                    "background_color" => ""
//                    "icon_url" => "-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXU5A1PIYQNqhpOSV-fRPasw8rsUFJ5KBFZv668FFU4naLOJzgUuYqyzIaIxa6jMOLXxGkHvcMjibmU99Sg3Qaw-hA_ZWrz ▶"
//                    "descriptions" => array:24 [▶]
//                    "tradable" => 1
//                    "name" => "Snakebite Case"
//                    "name_color" => "D2D2D2"
//                    "type" => "Base Grade Container"
//                    "market_name" => "Snakebite Case"
//                    "market_hash_name" => "Snakebite Case"
//                    "commodity" => 1
//                    "market_tradable_restriction" => 7
//                    "marketable" => 1
//                    "market_buy_country_restriction" => "FR"
//                    "app_icon" => "https://cdn.cloudflare.steamstatic.com/steamcommunity/public/images/apps/730/69f7ebe2735c366c65c0b33dae00e12dc40edbe4.jpg"
//                    "owner" => 0
//                ]
//                27812836380 => array:28 [▶]
//                ...
//            ]
//        ]
//    ]
//    "currency" => []
//    "hovers" => false
//    "app_data" => array:5 [▼
//        730 => array:4 [▼
//            "appid" => 730
//            "name" => "Counter-Strike: Global Offensive"
//            "icon" => "https://cdn.cloudflare.steamstatic.com/steamcommunity/public/images/apps/730/69f7ebe2735c366c65c0b33dae00e12dc40edbe4.jpg"
//            "link" => "https://steamcommunity.com/app/730"
//        ]
//        1259980 => array:4 [▶]
//        ...
//    ]
//    "last_time" => 1669500518
//    "last_listing" => "4066216491414679545"
//]