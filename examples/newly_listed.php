<?php
require __DIR__ . '/../vendor/autoload.php';

use SteamApi\SteamApi;


$api = new SteamApi();

$options = [
//    'country' => 'US',                        //optional
//    'language' => 'english',                  //optional
//    'currency' => 1                           //optional
];


dd($api->detailed()->getNewlyListed($options));