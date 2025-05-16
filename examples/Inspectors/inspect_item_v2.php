<?php
require __DIR__ . '/../../vendor/autoload.php';

use SteamApi\SteamApi;


$api = new SteamApi();

$inspectLink = "steam://rungame/730/76561202255233023/+csgo_econ_action_preview%20S76561198116851887A38092326942D13875065061064445233";


dd($api->detailed()->inspectItemV2($inspectLink));