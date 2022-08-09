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
  - [Steam methods](#steam-methods)
    - [Market Listings](#market-listings)
    - [Item Sale History](#item-sale-history)
    - [Item Pricing](#item-pricing)
    - [Search Items](#search-items)
    - [Item Listings](#item-listings)
    - [Newly Listed](#newly-listed)
    - [Item Orders Histogram](#item-orders-histogram)
    - [Item Name ID](#item-name-id)
    - [User Inventory](#user-inventory)
    - [User Inventory V2](#user-inventory-v2)
  - [Inspect methods](#inspect-methods)
    - [Inspect Item](#inspect-item)
    - [Inspect Item V2](#inspect-item-v2)
  - [Technical methods](#technical-methods)
    - [Currency List](#currency-list)
    - [Exterior List](#exterior-list)
    - [User Agents List](#user-agents-list)
    - [Next IP](#next-ip)
- [Proxy](#proxy)
- [Detailed response](#detailed-response)

---

Installation
------------

### With composer

Run this text in a console to install this package:

```
composer require allyans3/steam-market-api-v2
```

This package currently offers 10 API calls you can make to Steam, 2 APIs to for inspecting items and 5 technical methods.

Note
----

All methods don't have delays. If you are using some method in a cycle, please use this in-built php function to prevent steam block for a few minutes:
```
sleep(rand(12,16));
```
Recommended 12 (5 req/min) and more seconds.

Creating new object
-------------------

```
$api = new SteamApi();
```

Usage
-----

### Steam methods

---
#### Market Listings

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
            "class_id"      => "1738152281"
            "instance_id"   => "188530170"
            "name"          => "StatTrak™ AWP | Redline (Minimal Wear)"
            "image"         => "https://steamcommunity-a.akamaihd.net/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot621FAR17PLfYQJB496klb-HnvD8J_XSkDkB68Ani-qQpNmkigC1-EM4azj7IIadc1NtZVvX-QLsl7-7gce4ot2XngYgmyTY ◀"
            "image_large"   => "https://steamcommunity-a.akamaihd.net/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot621FAR17PLfYQJB496klb-HnvD8J4Tdl3lW7YtyjLuR9omjiVfl-kZtMW2iJ4bBelc2ZVjY-wTtxe3ohsXu6sydzSNnpGB8shVvZCcj ◀"
            "currency"      => "USD"
            "price"         => 146.28
            "price_text"    => "$146.28"
            "sell_listings" => 29
            "type"          => "StatTrak™ Classified Sniper Rifle"
            "exterior"      => "Minimal Wear"
        },
        ...
    ]
]
```

#### Item Sale History

```
$options = [
    'market_hash_name' => "AK-47 | The Empress (Field-Tested)",
];

$response = $api->getSaleHistory(730, $options);
```

This will return the lifetime sales history for an item by timestamp. Price in USD. Timestamp GMT+0.

```
[ 
    "time"   => 1505433600
    "price"  => 150.858
    "volume" => 35
]
```

#### Item Pricing


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
    "lowest_price_text" => "$36.66"
    "median_price"     => 34.56
    "median_price_text" => "$34.56"
]
```

#### Search Items

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

❗❗❗ In release `v3.2` added an optional `filters` key and more items in response arrays:

```
$options = [
    'start' => 0,
    'count' => 100,
    'query' => '',
    'exact' => true,
    'filters' => [
        'category_730_Type[]' => 'tag_Type_CustomPlayer' //For finding Agent type
    ]
];

$response = $api->searchItems(730, $options);
```

Response:

```
[ 
    "start" => 0
    "pagesize" => 100
    "total_count" => 63
    "items" => [
        0 => [
            "class_id"         => "4578725471"
            "instance_id"      => "519977179"
            "name"             => "Cmdr. Frank 'Wet Sox' Baroud | SEAL Frogman"
            "exterior"         => ""
            "name_color"       => "D2D2D2"
            "background_color" => ""
            "image"            => "https://steamcommunity-a.akamaihd.net/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXA6Q1NL4kmrAlOA0_FVPCi2t_fUkRxNztUoreaLw52 ▶"
            "type"             => "Master Agent"
            "tradable"         => 1
            "commodity"        => 0
            "price"            => 12.21
            "price_text"       => "$12.21"
            "sell_listings"    => 92
        ],
        ...
    ]
]
```

#### Item Listings

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
            "image" => "https://community.cloudflare.steamstatic.com/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot7HxfDhnwMzJemkV09m7hJKOhOTLPr7Vn35cppMh2L2VrN-h2geyqhc-MD3xJYecIANrMwvZ8wK8wr3nhJC6vJ2dy3B9-n51Yx1fd-M/62fx62f"
            "imageLarge" => "https://community.cloudflare.steamstatic.com/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot7HxfDhnwMzJemkV09m7hJKOhOTLPr7Vn35cppMh2L2VrN-h2geyqhc-MD3xJYecIANrMwvZ8wK8wr3nhJC6vJ2dy3B9-n51Yx1fd-M"
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

#### Newly Listed

This method show only CS:GO items.

```
$options = [
    'country' => 'US',
    'language' => 'english',
    'currency' => 1,
];

$response = $api->getNewlyListed($options);
```

You'll get this response:

```
[
    0 => [
        "listing_id" => "3370369307670557422",
        "name" => "AWP | Acheron (Field-Tested)",
        "image" => "https://steamcommunity-a.akamaihd.net/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot621FA957P3dcjFH7c6Jh4uem_vnDKnUkmld_cBOh-zF_Jn4xlHm-0U6ZGv1coGTIwRsZAnY_lLvkuq-18To6JnPn3E26HMq7X7YzEGpwUYbDS-LXA0",
        "image_large" => "https://steamcommunity-a.akamaihd.net/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpot621FA957P3dcjFH7c6Jh4uem_vnDKnUkmld_cBOh-zF_Jn4t1i1uRQ5fTzxJIDDdQQ4N1_Wq1PsxL_ngZXt75mYmyA37iQks3yIzUa_iRlJPbFxxavJcvYnYZs",
        "inspectable" => true,
        "inspect_link" => "steam://rungame/730/76561202255233023/+csgo_econ_action_preview%20M3370369307670557422A23189662357D28759705069335587",
        "stickers" => [],
        "type" => "Mil-Spec Grade Sniper Rifle",
        "status" => 2,
        "price_with_fee" => 1.01,
        "publisher_fee" => 0.08,
        "steam_fee" => 0.04,
        "price_without_fee" => 0.89
    ],
    ...
]
```

#### Item Orders Histogram

❗❗❗ In release `v3.1` changed values to float for `highest_buy_order` and `lowest_sell_order`.

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
    "highest_buy_order" => 27.11
    "lowest_sell_order" => 28.87
    "buy_order_summary" => 6626
    "sell_order_summary" => 184
    "buy_order_graph" => [
        0 => [
            "price" => 27.11
            "volume" => 3
            "description" => "3 buy orders at $27.11 or higher"
        ],
        ...
    ]
    "sell_order_graph" => [
        0 => [
            "price" => 28.87
            "volume" => 1
            "description" => "1 sell orders at $28.87 or lower"
        ],
        ...
    ]
    "buy_order_table" => [
        0 => [
            "price" => 27.11
            "price_text" => "$27.11"
            "count" => 3
        ],
        ...
    ]
    "sell_order_table" => [
        0 => [
            "price" => 28.87
            "price_text" => "$28.87"
            "count" => 1
        ],
        ...
    ]
    "graph_max_y" => 700
    "graph_min_x" => 23.66
    "graph_max_x" => 41.98
    "price_prefix" => "$"
    "price_suffix" => ""
]
```

#### Item Name ID

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

#### User Inventory

This method return user inventory by SteamID64.

```
$options = [
    'steamId' => '76561197986603983',
    'contextId' => 2,
    'count'   => 50,
    'language' => 'english',
    'startAssetId' => ''
];

$response = $api->getUserInventory(730, $options);
```

You'll receive inventory items:

```
[
    0 => [
        "assetid" => "20213922670",
        "classid" => "1815180002",
        "instanceid" => "188530139",
        "amount" => "1",
        "slot" => 1,
        "name" => "★ Karambit | Gamma Doppler (Factory New)",
        "nameColor" => "8650AC",
        "type" => "★ Covert Knife",
        "image" => "https://steamcommunity-a.akamaihd.net/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpovbSsLQJf2PLacDBA5ciJlY20kPb5PrrukmRB-Ml0mNbR_Y3mjQaLpxo7Oy3tcYKVcQRsZF_Q-FTow-zs0Jft7czNmiNluyV35nrbyR2_1UlPaOFp1uveFwtI0RP3qg",
        "imageLarge" => "https://steamcommunity-a.akamaihd.net/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpovbSsLQJf2PLacDBA5ciJlY20kPb5PrrukmRB-Ml0mNbR_Y3mjQaLpxo7Oy2ceNfXJVMgNFzQ-VPsxOnvh5Pqvp_KnHMy63Emti7bnhDigh1KOO1n0aSdT1iYVLsJQvdgMbUfwA",
        "image_cf" => "https://community.cloudflare.steamstatic.com/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpovbSsLQJf2PLacDBA5ciJlY20kPb5PrrukmRB-Ml0mNbR_Y3mjQaLpxo7Oy3tcYKVcQRsZF_Q-FTow-zs0Jft7czNmiNluyV35nrbyR2_1UlPaOFp1uveFwtI0RP3qg",
        "imageLarge_cf" => "https://community.cloudflare.steamstatic.com/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpovbSsLQJf2PLacDBA5ciJlY20kPb5PrrukmRB-Ml0mNbR_Y3mjQaLpxo7Oy2ceNfXJVMgNFzQ-VPsxOnvh5Pqvp_KnHMy63Emti7bnhDigh1KOO1n0aSdT1iYVLsJQvdgMbUfwA",
        "withdrawable_at" => 7,
        "marketable" => true,
        "tradable" => true,
        "commodity" => false,
        "nameTag" => "StrikeR's PricK",
        "inspectLink" => "steam://rungame/730/76561202255233023/+csgo_econ_action_preview%20S76561202255233023A20213922670D7506381567363227325",
        "condition" => "Factory New",
        "float" => 0.060837019234896,
        "paintseed" => 905,
        "paintindex" => 570,
        "stickers" => []
    ],
    ...
]
```

#### User Inventory V2

This method return FULL user inventory by SteamID64.

```
$options = [
    'steamId' => '76561197986603983',
    'contextId' => 2,
];

$response = $api->getUserInventoryV2(730, $options);
```

You'll receive inventory items:

```
[
    0 => [
        "assetid" => "20213922670",
        "classid" => "1815180002",
        "instanceid" => "188530139",
        "amount" => "1",
        "hide_in_china" => false,
        "slot" => 1,
        "name" => "★ Karambit | Gamma Doppler (Factory New)",
        "nameColor" => "8650AC",
        "type" => "★ Covert Knife",
        "image" => "https://steamcommunity-a.akamaihd.net/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpovbSsLQJf2PLacDBA5ciJlY20kPb5PrrukmRB-Ml0mNbR_Y3mjQaLpxo7Oy3tcYKVcQRsZF_Q-FTow-zs0Jft7czNmiNluyV35nrbyR2_1UlPaOFp1uveFwtI0RP3qg",
        "imageLarge" => "https://steamcommunity-a.akamaihd.net/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpovbSsLQJf2PLacDBA5ciJlY20kPb5PrrukmRB-Ml0mNbR_Y3mjQaLpxo7Oy2ceNfXJVMgNFzQ-VPsxOnvh5Pqvp_KnHMy63Emti7bnhDigh1KOO1n0aSdT1iYVLsJQvdgMbUfwA",
        "image_cf" => "https://community.cloudflare.steamstatic.com/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpovbSsLQJf2PLacDBA5ciJlY20kPb5PrrukmRB-Ml0mNbR_Y3mjQaLpxo7Oy3tcYKVcQRsZF_Q-FTow-zs0Jft7czNmiNluyV35nrbyR2_1UlPaOFp1uveFwtI0RP3qg",
        "imageLarge_cf" => "https://community.cloudflare.steamstatic.com/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpovbSsLQJf2PLacDBA5ciJlY20kPb5PrrukmRB-Ml0mNbR_Y3mjQaLpxo7Oy2ceNfXJVMgNFzQ-VPsxOnvh5Pqvp_KnHMy63Emti7bnhDigh1KOO1n0aSdT1iYVLsJQvdgMbUfwA",
        "withdrawable_at" => "7",
        "cacheExpiration" => "",
        "marketable" => true,
        "tradable" => true,
        "commodity" => false,
        "nameTag" => "StrikeR's PricK",
        "inspectLink" => "steam://rungame/730/76561202255233023/+csgo_econ_action_preview%20S76561202255233023A20213922670D7506381567363227325",
        "condition" => "Factory New",
        "float" => 0.060837019234896,
        "paintseed" => 905,
        "paintindex" => 570,
        "stickers" => []
    ]
    ...
]
```

### Inspect methods

---

#### Inspect Item

Source API: https://github.com/csgofloat/CSGOFloat-Inspect

```

$options = [
    'inspect_link' => 'steam://rungame/730/76561202255233023/+csgo_econ_action_preview%20M3130517023148833575A18217556235D3377922844091506969',
    'detailed'     => false,
    'minimal'      => false
];

$response = $api->inspectItem($options);
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

#### Inspect Item V2

```

$options = [
    'inspect_link' => 'steam://rungame/730/76561202255233023/+csgo_econ_action_preview%20M3130517023148833575A18217556235D3377922844091506969',
    'detailed'     => false,
];

$response = $api->inspectItemV2($options);
```

You'll get this response:

```
[
    "iteminfo" => [
        "accountid" => null
        "itemid" => "18217556235"
        "defindex" => 7
        "paintindex" => 675
        "rarity" => 6
        "quality" => 4
        "paintseed" => 725
        "killeaterscoretype" => null
        "killeatervalue" => null
        "customname" => null
        "stickers" => [
            0 => [
                "slot" => 0
                "stickerId" => 4217
                "wear" => null
                "scale" => null
                "rotation" => null
                "tintId" => null
                "codename" => "berlin2019_signature_golden_foil"
                "name" => "Golden (Foil) | Berlin 2019"
            ]
            1 => [
                "slot" => 2
                "stickerId" => 260
                "wear" => null
                "scale" => null
                "rotation" => null
                "tintId" => null
                "codename" => "drugwarveteran"
                "name" => "Drug War Veteran"
            ]
        ]
        "inventory" => 75
        "origin" => 8
        "questid" => null
        "dropreason" => null
        "musicindex" => null
        "s" => "0"
        "a" => "18217556235"
        "d" => "3377922844091506969"
        "m" => "3130517023148833575"
        "floatvalue" => 0.35344177484512
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
    "success" => true
]
```

### Technical methods

---

#### Currency List

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

#### Exterior List

```
$response = $api->getExteriorList();
```

You'll receive exterior list:

```
[
    "(Factory New)" => "Factory New"
    "(Minimal Wear)" => "Minimal Wear"
    "(Field-Tested)" => "Field-Tested"
    "(Well-Worn)" => "Well-Worn"
    "(Battle-Scarred)" => "Battle-Scarred"
]
```

#### User Agents List

This method return User Agents List for this browsers: Chrome, Edge, Firefox, IE, Opera, Safari

```
$response = $api->getUserAgents('Chrome');
```

You'll receive user agents list:

```
[
    0 => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36",
    1 => "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36",
    2 => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.157 Safari/537.36",
    3 => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36",
    4 => "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36",
    5 => "Mozilla/5.0 (Windows NT 10.0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36",
    6 => "Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/74.0.3729.169 Safari/537.36",
    7 => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36",
    8 => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.88 Safari/537.36",
    9 => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.108 Safari/537.36",
    10 => "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/79.0.3945.130 Safari/537.36",
    ...
]
```

#### Next Ip

If you are using for the first time you will get the first item. If you have reached the last item then the first item will be returned to you.

```
$proxyList = [
    "46.218.155.194:3128",
    "1.32.41.37:8080",
    "114.5.35.98:38554",
    "175.103.46.161:3888",
    "203.210.84.59:80",
    "113.53.60.255:8080",
];

$response = $api->getNextIp($proxyList);
```

You'll receive next ip:

```
"46.218.155.194:3128"
```

Proxy
-----
In release `v2.2` added a third optional `$proxy` parameter where you can pass cURL parameters as in the example:

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

Detailed response
----------------
In release `v2.9` added an optional `detailed` key for `$options` parameter.

```
$options = [
    'start'            => 0,
    'count'            => 100,
    'market_hash_name' => "AK-47 | The Empress (Field-Tested)",
    'detailed'         => true
];

$response = $api->getItemListings(730, $options);
```

You'll receive detailed information about response:

```
[
    "request_headers" => [
        "http_code" => "GET /market/listings/730/AK-47%20%7C%20The%20Empress%20%28Field-Tested%29/render?query=&start=0&count=100&currency=1&country=EN&language=english&filter= HTTP/1.",
        "Host" => "steamcommunity.com",
        "Accept" => "*/*"
    ]
    "headers" => [
        "http_code" => "HTTP/1.1 200 OK",
        "Server" => "nginx",
        "Content-Type" => "application/json; charset=utf-8",
        "X-Frame-Options" => "SAMEORIGIN",
        "Cache-Control" => "public,max-age=90",
        "Expires" => "Mon, 30 Aug 2021 22:34:24 GMT",
        "Last-Modified" => "Mon, 30 Aug 2021 22:31:30 GMT",
        "Date" => "Mon, 30 Aug 2021 22:32:54 GMT",
        "Transfer-Encoding" => "chunked",
        "Connection" => "Transfer-Encoding"
    ]
    "response" => [
        "start" => 0,
        "pagesize" => "100",
        "total_count" => 273,
        "items" => [
            ...
        ]
    ],
    "error" => "",
    "remote_ip" => "108.86.128.186",
    "code" => 200,
    "url" => "https://steamcommunity.com/market/listings/730/AK-47%20%7C%20The%20Empress%20%28Field-Tested%29/render?query=&start=0&count=100&currency=1&country=EN&language=english&filter=",
    "total_time" => "767"
]
```