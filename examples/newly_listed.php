<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
    'country' => 'US',                        //optional
    'language' => 'english',                  //optional
    'currency' => 1                           //optional
];


dd($api->detailed()->getNewlyListed($options));





// Example response:
//
//"response" => array:11 [▼
//    "success" => true
//    "more" => false
//    "results_html" => false
//    "listinginfo" => array:10 [▼
//        4081979090112044859 => array:18 [▼
//            "listingid" => "4081979090112044859"
//            "price" => 69
//            "fee" => 9
//            "publisher_fee_app" => 730
//            "publisher_fee_percent" => "0.100000001490116119"
//            "currencyid" => 2005
//            "steam_fee" => 3
//            "publisher_fee" => 6
//            "converted_price" => 2
//            "converted_fee" => 2
//            "converted_currencyid" => 2001
//            "converted_steam_fee" => 1
//            "converted_publisher_fee" => 1
//            "converted_price_per_unit" => 2
//            "converted_fee_per_unit" => 2
//            "converted_steam_fee_per_unit" => 1
//            "converted_publisher_fee_per_unit" => 1
//            "asset" => array:6 [▼
//                "currency" => 0
//                "appid" => 730
//                "contextid" => "2"
//                "id" => "27796309406"
//                "amount" => "1"
//                "market_actions" => array:1 [▼
//                    0 => array:2 [▼
//                        "link" => "steam://rungame/730/76561202255233023/+csgo_econ_action_preview%20M%listingid%A%assetid%D5657296460488317091"
//                        "name" => "Inspect in Game..."
//                    ]
//                ]
//            ]
//        ]
//        4081979090112285419 => array:18 [▶]
//        ...
//    ]
//    "purchaseinfo" => []
//    "assets" => array:3 [▼
//        730 => array:1 [▼
//            2 => array:4 [▼
//                27796309406 => array:28 [▼
//                    "currency" => 0
//                    "appid" => 730
//                    "contextid" => "2"
//                    "id" => "27796309406"
//                    "classid" => "5087357344"
//                    "instanceid" => "188530139"
//                    "amount" => "1"
//                    "status" => 2
//                    "original_amount" => "1"
//                    "unowned_id" => "27796309406"
//                    "unowned_contextid" => "2"
//                    "background_color" => ""
//                    "icon_url" => "-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXQ9QVcJY8gulRPQV6CF7b9mMrFX2J8KghY-OmmfQUzg6WaIWsatNizl9jew6KjMO2IlT4E7pUm3LGRpomi2Qbt-kVyIzek ▶"
//                    "icon_url_large" => "-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXQ9QVcJY8gulRPQV6CF7b9mMrFX2J8KghYibakOQBlnaqfJWgSvd7il9XZlvHwYL7Sz2pV6sAl27-VodX2jlXhrUc5Z2z6 ▶"
//                    "descriptions" => array:5 [▶]
//                    "tradable" => 0
//                    "actions" => array:1 [▶]
//                    "name" => "Sticker | Evil Geniuses (Holo) | 2020 RMR"
//                    "name_color" => "D2D2D2"
//                    "type" => "Remarkable Sticker"
//                    "market_name" => "Sticker | Evil Geniuses (Holo) | 2020 RMR"
//                    "market_hash_name" => "Sticker | Evil Geniuses (Holo) | 2020 RMR"
//                    "market_actions" => array:1 [▶]
//                    "commodity" => 1
//                    "market_tradable_restriction" => 7
//                    "marketable" => 1
//                    "app_icon" => "https://cdn.cloudflare.steamstatic.com/steamcommunity/public/images/apps/730/69f7ebe2735c366c65c0b33dae00e12dc40edbe4.jpg"
//                    "owner" => 0
//                ]
//                27796309413 => array:28 [▶]
//                ...
//            ]
//        ]
//        753 => array:1 [▶]
//        ...
//    ]
//    "currency" => []
//    "hovers" => false
//    "app_data" => array:8 [▼
//        730 => array:4 [▼
//            "appid" => 730
//            "name" => "Counter-Strike: Global Offensive"
//            "icon" => "https://cdn.cloudflare.steamstatic.com/steamcommunity/public/images/apps/730/69f7ebe2735c366c65c0b33dae00e12dc40edbe4.jpg"
//            "link" => "https://steamcommunity.com/app/730"
//        ]
//        322170 => array:4 [▶]
//        ...
//    ]
//    "last_time" => 1669499966
//    "last_listing" => "4081979090112285419"
//]