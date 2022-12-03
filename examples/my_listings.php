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

dd($api->detailed()->withCookies($cookies)->getMyListings($options));





// Example response:
//
//"response" => array:10 [▼
//    "success" => true
//    "pagesize" => 30
//    "total_count" => 0
//    "assets" => []
//    "start" => 0
//    "num_active_listings" => 0
//    "listings" => []
//    "listings_on_hold" => []
//    "listings_to_confirm" => []
//    "buy_orders" => []
//]