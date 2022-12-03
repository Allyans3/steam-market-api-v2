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

// Cookies for steamcommunity.com
$cookies = 'sessionid=*********; steamCountry=****************; steamLoginSecure=**************;';

dd($api->detailed()->withCookies($cookies)->getUserInventoryAuth(Apps::CSGO_ID, $options));