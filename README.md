# Steam Market API V2

Source: https://github.com/JaxWilko/steam-market-api

Menu
----
---

- [Installation](#installation)
    - [With composer](#with-composer)
- [Note](#note)
- [Creating new object](#creating-new-object)
- [Usage](#usage)
    - [Market Listings](#market-listings)
    - [Item Sale History](#item-sale-history)
    - [Item Current Price](#item-current-price)
    - [Search Items](#search-items)
    - [Inspect Item](#inspect-item)
    - [Item Listings](#item-listings)
    - [Item Orders Histogram](#item-orders-histogram)
    - [Item Name ID](#item-name-id)
    - [Currency List](#currency-list)
    - [Condition List](#condition-list)
- [Proxy](#proxy)

---

Installation
------------

### With composer

Run this text in console to install this package:

```
composer require allyans3/steam-market-api-v2
```

This package currently offers 7 API calls you can make to Steam, 1 API to CSGOFloat-API and 2 technical methods.

Note
----

All methods don't have delays. If you are using some method in cycle, please use this in-built php function to prevent steam block for a few minutes:
```
sleep(rand(10,16));
```
Recommended 10 and more seconds.

Creating new object
-------------------

```
$api = new SteamApi();
```

Usage
-----

### Market Listings

```
$options = [
    'start'       => 0,
    'count'       => 100,
    'sort_column' => 'price',
    'sort_dir'    => 'asc'
];

$response = $api->getMarketListings(730, $options);
```

This will return a list of 100 items, you'll need to change the `start` option to cycle through the complete list of item.

You'll get 3 technical fields and array of items:

```
[
    "start"       => 0
    "pagesize"    => 100
    "total_count" => 14941
    "items" => [
        0 => [
            "name"          => "AK-47 | The Empress (Field-Tested)"
            "image"         => "https://steamcommunity-a.akamaihd.net/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot7HxfDhnwMzJemkV09m7hJKOhOTLPr7Vn35cppMh2L2VrN-h2geyqhc-MD3xJYecIANrMwvZ8wK8wr3nhJC6vJ2dy3B9-n51Yx1fd-M"           
            "currency"      => "USD"
            "price"         => 37.37
            "price_text"    => "$37.37"
            "sell_listings" => 246
            "type"          => "Covert Rifle"
            "condition"     => "Field-Tested"
        },
        ...
    ]
]
```

### Item Sale History

```
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
$options = [
    'market_hash_name' => "AK-47 | The Empress (Field-Tested)",
    'currency'         => 1
];

$response = $api->getItemPricing(730, $options);
```

This will return the lowest and median price for an item.

```
[ 
    "success"          => true
    "volume"           => "121"
    "lowest_price"     => 36.66
    "lowest_price_str" => "$36.66"
    "median_price"     => 34.56
    "median_price_str" => "$34.56"
]
```

### Search Items

```
$options = [
    'start' => 0,
    'count' => 100,
    'query' => 'AK-47 | The Empress',
    'exact' => true
];

$response = $api->searchItems(730, $options);
```

You'll get 3 technical fields and array of items:

```
[ 
    "start" => 0
    "pagesize" => 100
    "total_count" => 10
    "items" => [
        0 => [
            "name"          => "AK-47 | The Empress (Field-Tested)"
            "image"         => "https://steamcommunity-a.akamaihd.net/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot7HxfDhnwMzJemkV09m7hJKOhOTLPr7Vn35cppMh2L2VrN-h2geyqhc-MD3xJYecIANrMwvZ8wK8wr3nhJC6vJ2dy3B9-n51Yx1fd-M"           
            "price"         => 37.37
            "price_text"    => "$37.37"
            "sell_listings" => 246
            "type"          => "Covert Rifle"
            "condition"     => "Field-Tested"
        ],
        ...
    ]
]
```

### Inspect Item

Source API: https://github.com/csgofloat/CSGOFloat-Inspect

```
$inspectLink = "steam://rungame/730/76561202255233023/+csgo_econ_action_preview%20M3130517023148833575A18217556235D3377922844091506969";

$response = $api->inspectItem($inspectLink);
```

You'll get this response:

```
[
    "iteminfo" => [
        "origin" => 8
        "quality" => 4
        "rarity" => 6
        "a" => "18217556235"
        "d" => "11973598228597186897"
        "paintseed" => 725
        "defindex" => 7
        "paintindex" => 675
        "stickers" => [
            0 => [
                "stickerId" => 4217
                "slot" => 0
                "codename" => "berlin2019_signature_golden_foil"
                "material" => "berlin2019/sig_golden_foil"
                "name" => "Golden (Foil) | Berlin 2019"
            ]
            1 => [
                "stickerId" => 260
                "slot" => 2
                "codename" => "drugwarveteran"
                "material" => "community02/drugwarveteran"
                "name" => "Drug War Veteran"
            ]
        ]
        "floatid" => "18035389401"
        "floatvalue" => 0.35344177484512
        "s" => "76561198096112563"
        "m" => "0"
        "imageurl" => "http://media.steampowered.com/apps/730/icons/econ/default_generated/weapon_ak47_gs_ak47_empress_light_large.f81d0b07dca381635c89f74bcdb6b64a6da6e81c.png"
        "min" => 0
        "max" => 1
        "weapon_type" => "AK-47"
        "item_name" => "The Empress"
        "rarity_name" => "Covert"
        "quality_name" => "Unique"
        "origin_name" => "Found in Crate"
        "wear_name" => "Field-Tested"
        "full_item_name" => "AK-47 | The Empress (Field-Tested)"
    ]
]
```

### Item Listings

```
$options = [
    'start' => 0,
    'count' => 100,
    'currency' => 1,
    'market_hash_name' => "AK-47 | The Empress (Field-Tested)"
    'filter' => ''
];

$response = $api->getItemListings(730, $options);
```

You'll get 3 technical fields and array of items:

```
[
    "start" => 0
    "pagesize" => "100"
    "total_count" => 165
    "items" => [
        0 => [
            "listingId" => "3136147247424375927"
            "name" => "AK-47 | The Empress (Field-Tested)"
            "image" => "https://steamcommunity-a.akamaihd.net/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot7HxfDhn ▶"
            "price_with_fee" => 37.37
            "price_with_fee_str" => "$37.37"
            "price_with_publisher_fee_only" => 35.75
            "price_with_publisher_fee_only_str" => "$35.75"
            "price_without_fee" => 32.5
            "price_without_fee_str" => "$32.50"
            "inspectLink" => "steam://rungame/730/76561202255233023/+csgo_econ_action_preview%20M3136147247424375927A19190892996D9387202219111148413"
        ],
        ...    
    ]
]
```

### Item Orders Histogram

```
$options = [
    'country' => 'US',
    'language' => 'english',
    'currency' => 1,
    'item_nameid' => 175917356
];

$response = $api->getItemOrdersHistogram($options);
```

You'll get this response:

```
[
    "highest_buy_order" => 4033
    "lowest_sell_order" => 4343
    "buy_order_summary" => 5123
    "sell_order_summary" => 163
    "buy_order_graph" => [
        0 => [
            "price" => 40.33
            "volume" => 1
            "description" => "1 buy orders at $40.33 or higher"
        ],
        ...
    ]
    "sell_order_graph" => [
        0 => [
            "price" => 43.43
            "volume" => 3
            "description" => "3 sell orders at $43.43 or lower"
        ],
        ...
    ]
    "graph_max_y" => 500
    "graph_min_x" => 33.88
    "graph_max_x" => 80.96
    "price_prefix" => "$"
    "price_suffix" => ""
]
```

### Item Name ID

This method needs in order to get the `item_nameid` for the method above (Item Orders Histogram).

```
$options = [
    'market_hash_name' => "AK-47 | The Empress (Field-Tested)"
];

$response = $api->getItemNameId(730, $options);
```

You'll get the `item_nameid` number:

```
175917356
```

### Currency List

```
$response = $api->getCurrencyList();
```

You'll receive currency list:

```
    0 => "USD"
    1 => "USD"
    2 => "GBP"
    3 => "EUR"
    4 => "CHF"
    5 => "RUB"
    6 => "PLN"
    ...
```

### Condition List

```
$response = $api->getConditionList();
```

You'll receive condition list:

```
[
    "(Factory New)" => "Factory New"
    "(Minimal Wear)" => "Minimal Wear"
    "(Field-Tested)" => "Field-Tested"
    "(Well-Worn)" => "Well-Worn"
    "(Battle-Scarred)" => "Battle-Scarred"
]
```

Proxy
-----
In release `v2.2` added a second optional `$proxy` parameter where you can pass cURL parameters as in the example:

```
$proxy = [
    CURLOPT_PROXY => '81.201.60.130:80',
    CURLOPT_PROXYTYPE => CURLPROXY_HTTP,
    CURLOPT_TIMEOUT => 9,
    CURLOPT_CONNECTTIMEOUT => 6,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36',
    ...
];

$response = $api->getMarketListings(730, $options, $proxy);
```
