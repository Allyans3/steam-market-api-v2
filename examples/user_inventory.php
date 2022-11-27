<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\Configs\Apps;
use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
    'steam_id' => 76561197986603983,
    'context_id' => 2,                           //optional
    'count' => 100,                              //optional
    'language' => 'english',                     //optional
    'start_asset_id' => null                     //optional
];

dd($api->detailed()->getUserInventory(Apps::CSGO_ID, $options));





// Example response:
//
//"response" => array:7 [▼
//    "assets" => array:5 [▼
//        0 => array:6 [▼
//            "appid" => 730
//            "contextid" => "2"
//            "assetid" => "26866875232"
//            "classid" => "4958202237"
//            "instanceid" => "188530139"
//            "amount" => "1"
//        ]
//        1 => array:6 [▶]
//        ...
//    ]
//    "descriptions" => array:5 [▼
//        0 => array:20 [▼
//            "appid" => 730
//            "classid" => "4958202237"
//            "instanceid" => "188530139"
//            "currency" => 0
//            "background_color" => ""
//            "icon_url" => "-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXQ9Q1LO5kNoBhSQl-fROuh28rQR1R2KQFoprOrFAZsyuv3IWsMvd_gx9fdxKDxZb-JwG1TvZxwj7vFoI-sjQ23_UBsMGym ▶"
//            "icon_url_large" => "-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXQ9Q1LO5kNoBhSQl-fROuh28rQR1R2KQFoprOrFAZsyuv3IWt94N2kk4XFw6XxMOnTw2pTvZQniLiY8Y6njFDt8ktrZDii ▶"
//            "descriptions" => array:2 [▶]
//            "tradable" => 1
//            "actions" => array:1 [▶]
//            "name" => "Black Mesa Pin"
//            "name_color" => "D2D2D2"
//            "type" => "High Grade Collectible"
//            "market_name" => "Black Mesa Pin"
//            "market_hash_name" => "Black Mesa Pin"
//            "market_actions" => array:1 [▶]
//            "commodity" => 1
//            "market_tradable_restriction" => 7
//            "marketable" => 1
//            "tags" => array:3 [▶]
//        ]
//        1 => array:20 [▶]
//        ...
//    ]
//    "more_items" => 1
//    "last_assetid" => "26866620182"
//    "total_inventory_count" => 600
//    "success" => 1
//    "rwgrsn" => -2
//]