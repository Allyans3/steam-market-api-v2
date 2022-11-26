<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\Configs\Apps;
use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
    'query' => 'AK-47',                                  //optional
    'start' => 0,                                        //optional
    'count' => 100,                                      //optional
    'search_descriptions' => false,                      //optional
    'exact' => true,                                     //optional
    'filter' => [                                        //optional
        'category_730_Type' => ['tag_CSGO_Type_Rifle'],  // Search Riffle
        'category_730_Exterior' => [                     // by exterior
            'tag_WearCategory2',                         // "Field-Tested"
            'tag_WearCategory1'                          // and "Minimal Wear"
        ]
    ]
];

dd($api->detailed()->searchItems(Apps::CSGO_ID, $options));





// Example response:
//
//"response" => array:6 [▼
//    "success" => true
//    "start" => 0
//    "pagesize" => 100
//    "total_count" => 147
//    "searchdata" => array:6 [▼
//        "query" => ""AK-47""
//        "search_descriptions" => false
//        "total_count" => 147
//        "pagesize" => 100
//        "prefix" => "searchResults"
//        "class_prefix" => "market"
//    ]
//    "results" => array:100 [▼
//        0 => array:9 [▼
//            "name" => "AK-47 | Jaguar (Field-Tested)"
//            "hash_name" => "AK-47 | Jaguar (Field-Tested)"
//            "sell_listings" => 84
//            "sell_price" => 2464
//            "sell_price_text" => "$24.64"
//            "app_icon" => "https://cdn.cloudflare.steamstatic.com/steamcommunity/public/images/apps/730/69f7ebe2735c366c65c0b33dae00e12dc40edbe4.jpg"
//            "app_name" => "Counter-Strike: Global Offensive"
//            "asset_description" => array:12 [▼
//                "appid" => 730
//                "classid" => "5081962813"
//                "instanceid" => "188530139"
//                "background_color" => ""
//                "icon_url" => "-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot7HxfDhjxszYcDNW5Nmkq4GAw6DLPr7Vn35cpschiOiTpNvx2QzmqUJkZDqn ▶"
//                "tradable" => 1
//                "name" => "AK-47 | Jaguar (Field-Tested)"
//                "name_color" => "D2D2D2"
//                "type" => "Covert Rifle"
//                "market_name" => "AK-47 | Jaguar (Field-Tested)"
//                "market_hash_name" => "AK-47 | Jaguar (Field-Tested)"
//                "commodity" => 0
//            ]
//            "sale_price_text" => "$23.57"
//        ]
//        1 => array:9 [▶]
//        ...
//    ]
//]