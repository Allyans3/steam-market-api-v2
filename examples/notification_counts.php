<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\SteamApi;


$api = new SteamApi();

$cookies = 'browserid=********; steamMachineAuth76561197986603983=****************; steamLoginSecure=**************; sessionid=*********';

dd($api->detailed()->withCookies($cookies)->getNotificationCounts());