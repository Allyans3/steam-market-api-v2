<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\Configs\Apps;
use SteamApi\SteamApi;


$api = new SteamApi();

dd($api->detailed()->getAppFilters(Apps::CSGO_ID));





// Example response:
//
//"response" => array:2 [▼
//    "success" => true
//    "facets" => array:16 [▼
//        "730_Exterior" => array:4 [▼
//            "appid" => 730
//            "name" => "Exterior"
//            "localized_name" => "Exterior"
//            "tags" => array:6 [▼
//                "WearCategory2" => array:2 [▼
//                    "localized_name" => "Field-Tested"
//                    "matches" => "1,650,254"
//                ]
//                "WearCategory1" => array:2 [▶]
//                "WearCategory4" => array:2 [▶]
//                "WearCategory3" => array:2 [▶]
//                "WearCategory0" => array:2 [▶]
//                "WearCategoryNA" => array:2 [▶]
//            ]
//        ]
//        "730_ItemSet" => array:4 [▶]
//        "730_PatchCapsule" => array:4 [▶]
//        "730_PatchCategory" => array:4 [▶]
//        "730_ProPlayer" => array:4 [▶]
//        "730_Quality" => array:4 [▶]
//        "730_Rarity" => array:4 [▶]
//        "730_SprayCapsule" => array:4 [▶]
//        "730_SprayCategory" => array:4 [▶]
//        "730_SprayColorCategory" => array:4 [▶]
//        "730_StickerCapsule" => array:4 [▶]
//        "730_StickerCategory" => array:4 [▶]
//        "730_Tournament" => array:4 [▶]
//        "730_TournamentTeam" => array:4 [▶]
//        "730_Type" => array:4 [▶]
//        "730_Weapon" => array:4 [▶]
//    ]
//]
