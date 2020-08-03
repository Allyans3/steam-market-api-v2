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

This will return a list of 100 items, you'll need to change the `start` option to cycle through the complete list of item.

Each item in the array will look like:
```
[
    "name"       => "AK-47 | The Empress (Field-Tested)"
    "image"      => "https://steamcommunity-a.akamaihd.net/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot7HxfDhnwMzJemkV09m7hJKOhOTLPr7Vn35cppMh2L2VrN-h2geyqhc-MD3xJYecIANrMwvZ8wK8wr3nhJC6vJ2dy3B9-n51Yx1fd-M"
    "curr_price" => "$41.92"
    "currency"   => "USD"
    "price"      => "41.92"
    "volume"     => 174
    "type"       => "Covert Rifle"
    "condition"  => "Field-Tested"
]
```

### Item Sale History

```
$api = new SteamApi();

$options = [
    'market_hash_name' => "AK-47 | The Empress (Field-Tested)",
];

$response = $api->getSaleHistory(730, $options);
```

This will return the lifetime sales history for an item by date.


```
[ 
    "sale_date"  => "2017-09-15"
    "sale_price" => 150.858
    "quantity"   => 35
]
```

### Item Current Price


```
$api = new SteamApi();

$options = [
    'market_hash_name' => "AK-47 | The Empress (Field-Tested)",
    'currency'         => 1
];

$response = $api->getItemPricing(730, $options);
```

This will return the lowest and median price for an item.

```
[ 
    "success" => true
    "lowest_price" => "$41.88"
    "volume" => "94"
    "median_price" => "$40.51"
]
```
