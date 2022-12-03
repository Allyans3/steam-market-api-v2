<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\SteamApi;


$api = new SteamApi();

// Cookies for steamcommunity.com
$cookies = 'sessionid=*********; steamCountry=****************; steamLoginSecure=**************;';

dd($api->detailed()->withCookies($cookies)->getNotificationCounts());