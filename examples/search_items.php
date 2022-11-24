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