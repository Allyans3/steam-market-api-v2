<?php
require __DIR__ . '/../../vendor/autoload.php';

use SteamApi\SteamApi;

$api = new SteamApi();

// Cookies for steamcommunity.com
$cookies = 'sessionid=*********; steamCountry=****************; steamLoginSecure=**************;';

$options = [
    'confirmations' => [
        [
            'id' => '12345678910',
            'key' => '4204671287461724681'
        ],
    ],
    'device_id' => "android:********-****-****-****-************",
    'steam_id' => '76561202255233023',
    'identity_secret' => "exa3m1ple/seC5ret=",
    'type' => 'allow'                                               //optional
];


dd($api->detailed()
       ->withCustomCurlOpts([CURLOPT_COOKIEFILE => 'absolute_path_to_cookie_file']) // with cookie file
       ->withCookies($cookies)                                                      // or with cookies from string or array
       ->sendMobileMultiConfAjax($options));