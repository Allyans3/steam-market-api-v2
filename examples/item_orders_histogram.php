<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\Configs\Apps;
use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
    'market_hash_name' => "AK-47 | Redline (Field-Tested)",
    'item_name_id' => 7178002
];

dd($api->detailed()->getItemOrdersHistogram(Apps::CSGO_ID, $options));