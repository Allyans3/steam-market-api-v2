<?php
require __DIR__ . '/../../vendor/autoload.php';

use SteamApi\SteamApi;

$api = new SteamApi();

// Cookies for steamcommunity.com
$cookies = 'sessionid=*********; steamCountry=****************; steamLoginSecure=**************;';

$tradeLink = 'https://steamcommunity.com/tradeoffer/new/?partner=152735263&token=g82gdgy2d';

$formData = [
    'partner_id' => 76531274732642783,         //optional
    'message' => 'message',                    //optional
    'my_items' => [
        [
            "app_id" => 730,
            "context_id" =>  "2",
            "amount" =>  1,
            "asset_id" => "28738189184"
        ],
    ],
    'partner_items' => []
];


dd($api->detailed()
       ->withCustomCurlOpts([CURLOPT_COOKIEFILE => 'absolute_path_to_cookie_file']) // with cookie file
       ->withCookies($cookies)                                                      // or with cookies from string or array
       ->createTradeOffer($tradeLink, $formData));
