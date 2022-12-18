<?php
require __DIR__ . '/../../vendor/autoload.php';

use SteamApi\SteamApi;

$api = new SteamApi();

// Cookies for steamcommunity.com
$cookies = 'sessionid=*********; steamCountry=****************; steamLoginSecure=**************;';


dd($api->detailed()
       ->withCustomCurlOpts([CURLOPT_COOKIEFILE => 'absolute_path_to_cookie_file']) // with cookie file
       ->withCookies($cookies)                                                      // or with cookies from string or array
       ->declineTradeOffer(5378282881));