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
    'filter' => ""                                         //optional
];

dd($api->detailed()->getItemListings(Apps::CSGO_ID, $options));





// Example response:
//
//"response" => array:4 [▼
//    "start" => 0
//    "page_size" => 10
//    "total_count" => 2760
//    "listings" => array:10 [▼
//        0 => array:4 [▼
//            "listing_id" => "4079727290296482257"
//            "asset" => array:12 [▼
//                "id" => "27771329193"
//                "class_id" => "5081816482"
//                "instance_id" => "188530139"
//                "market_hash_name" => "AK-47 | Slate (Field-Tested)"
//                "icon_url" => "-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot7HxfDhnwMzJemkV08ykm4aOhOT9PLXQmlRc7cF4n-SP8dyhiwK1_xU4ajygIdSdJgVoMlzRrFTqlea5hpK66ZvNmnI3vHUk4WGdwUJBbIpZ4g"
//                "icon_url_large" => "-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot7HxfDhnwMzJemkV08ykm4aOhOT9PLXQmlRc7cF4n-T--Y3nj1H6r0NoMG-iINDBe1NtZArYrlLokuru05Po6JWfynZl7yYmtn-JmUCxhQYMMLJKN_FGrA"
//                "stickers" => ""
//                "amount" => "1"
//                "status" => 2
//                "tradable" => 0
//                "marketable" => 1
//                "inspect_link" => "steam://rungame/730/76561202255233023/+csgo_econ_action_preview%20M4079727290296482257A27771329193D10119138550854773623"
//            ]
//            "original_price_data" => array:5 [▼
//                "currency_id" => 6
//                "currency" => "PLN"
//                "price_with_fee" => 12.13
//                "price_with_publisher_fee_only" => 11.61
//                "price_without_fee" => 10.56
//            ]
//            "price_data" => array:8 [▼
//                "currency_id" => 1
//                "currency" => "USD"
//                "price_with_fee" => 2.67
//                "price_with_fee_str" => "$2.67"
//                "price_with_publisher_fee_only" => 2.56
//                "price_with_publisher_fee_only_str" => "$2.56"
//                "price_without_fee" => 2.33
//                "price_without_fee_str" => "$2.33"
//            ]
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