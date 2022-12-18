<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
    'query' => "",
    'start' => 0,                                 //optional
    'count' => 30                                 //optional
];

// Cookies for steamcommunity.com
$cookies = 'sessionid=*********; steamCountry=****************; steamLoginSecure=**************;';


dd($api->detailed()
       ->withCustomCurlOpts([CURLOPT_COOKIEFILE => 'absolute_path_to_cookie_file']) // with cookie file
       ->withCookies($cookies)                                                      // or with cookies from string or array
       ->getMyHistory($options));





// Example response:
//
//"response" => array:8 [▼
//    "success" => true
//    "pagesize" => 30
//    "total_count" => 1000
//    "start" => 0
//    "assets" => array:1 [▶]
//    "events" => array:30 [▶]
//    "purchases" => array:20 [▶]
//    "listings" => array:20 [▶]
//]