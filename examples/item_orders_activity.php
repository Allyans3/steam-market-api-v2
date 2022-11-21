<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\Configs\Apps;
use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
    'market_hash_name' => "Recoil Case",
    'item_name_id' => 176321160
];

dd($api->detailed()->getItemOrdersActivity(Apps::CSGO_ID, $options));