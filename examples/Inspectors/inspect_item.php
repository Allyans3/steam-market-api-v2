<?php
require __DIR__ . '/../../vendor/autoload.php';

use SteamApi\SteamApi;


$api = new SteamApi();

$inspectLink = "steam://rungame/730/76561202255233023/+csgo_econ_action_preview%20M3267882520477718672A19612828092D2642074259339382751";


dd($api->detailed()->inspectItem($inspectLink));