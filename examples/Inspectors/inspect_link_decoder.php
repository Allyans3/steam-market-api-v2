<?php
require __DIR__ . '/../../vendor/autoload.php';

use SteamApi\SteamApi;


$api = new SteamApi();

// You can you full inspect link
$inspectLink = "steam://run/730//+csgo_econ_action_preview%2050408596D8C2E551485770CA527855605468DC89EAA5531067325F585040FE736D5C807AEE1590E79C6B386C20589AC76CB0";
// or item certificate
$itemCertificate = '50408596D8C2E551485770CA527855605468DC89EAA5531067325F585040FE736D5C807AEE1590E79C6B386C20589AC76CB0';
// The result will be the same
dd($api->inspectLinkDecoder($inspectLink), $api->inspectLinkDecoder($itemCertificate));


// Example response:

//array:12 [▼
//    "stickers" => array:1 [▼
//        0 => array:4 [▼
//            "slot" => 0
//            "sticker_id" => 4526
//            "offset_x" => -0.16680926084518
//            "offset_y" => 0.006247490644455
//        ]
//    ]
//    "keychains" => []
//    "variations" => []
//    "asset_id" => 48624706389
//    "def_index" => 7
//    "paint_index" => 282
//    "rarity" => 5
//    "quality" => 4
//    "paint_wear" => 0.34116017818451
//    "paint_seed" => 55
//    "inventory" => 60
//    "origin" => 8
//]