<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\Configs\Apps;
use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
    'market_hash_name' => "AK-47 | Redline (Field-Tested)"
];

dd($api->detailed()->getSaleHistory(Apps::CSGO_ID, $options));