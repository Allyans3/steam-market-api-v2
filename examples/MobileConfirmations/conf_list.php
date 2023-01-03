<?php
require __DIR__ . '/../../vendor/autoload.php';

use SteamApi\SteamApi;

$api = new SteamApi();

// Cookies for steamcommunity.com
$cookies = 'sessionid=*********; steamCountry=****************; steamLoginSecure=**************;';

$options = [
    'device_id' => "android:********-****-****-****-************",
    'steam_id' => '76561202255233023',
    'identity_secret' => "exa3m1ple/seC5ret="
];


dd($api->detailed()
       ->withCustomCurlOpts([CURLOPT_COOKIEFILE => 'absolute_path_to_cookie_file']) // with cookie file
       ->withCookies($cookies)                                                      // or with cookies from string or array                                                 // or with cookies from string or array
       ->getMobileConfList($options));