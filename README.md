# Steam Market API V2

Source: https://github.com/JaxWilko/steam-market-api

Installation
------------

### With composer

Run this text in console to install this package:

```
composer require allyans3/steam-market-api-v2
```
Usage
-----

### Market Listings

```
$api = new SteamApi();

$options = [
  'start'       => 0,
  'count'       => 100,
  'sort_column' => 'price',
  'sort_dir'    => 'asc'
];

$response = $api->getMarketListings(730, $options);
```
