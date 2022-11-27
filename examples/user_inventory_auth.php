<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\Configs\Apps;
use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
    'steam_id' => 76561197986603983,
    'context_id' => 2,                           //optional
    'language' => 'english',                     //optional
];

$cookies = 'browserid=********; steamMachineAuth76561197986603983=****************; steamLoginSecure=**************; sessionid=*********';

dd($api->detailed()->withCookies($cookies)->getUserInventoryAuth(Apps::CSGO_ID, $options));