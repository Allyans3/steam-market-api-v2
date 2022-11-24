<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\Configs\Apps;
use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
    'market_hash_name' => "Recoil Case",
    'item_name_id' => 176321160,
//    'country' => 'US',                   //optional
//    'language' => 'english',             //optional
//    'currency' => 1,                     //optional
//    'two_factor' => 0                    //optional
];

dd($api->detailed()->getItemOrdersActivity(Apps::CSGO_ID, $options));