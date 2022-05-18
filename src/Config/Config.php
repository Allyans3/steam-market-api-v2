<?php

namespace SteamApi\Config;

class Config
{
    const CURRENCY = [
        0 => 'USD',
        1 => 'USD',
        2 => 'GBP',
        3 => 'EUR',
        4 => 'CHF',
        5 => 'RUB',
        6 => 'PLN',
        7 => 'BRL',
        8 => 'JPY',
        9 => 'NOK',
        10 => 'IDR',
        11 => 'MYR',
        12 => 'PHP',
        13 => 'SGD',
        14 => 'THB',
        15 => 'VND',
        16 => 'KRW',
        17 => 'TRY',
        18 => 'UAH',
        19 => 'MXN',
        20 => 'CAD',
        21 => 'AUD',
        22 => 'NZD',
        23 => 'CNY',
        24 => 'INR',
        25 => 'CLP',
        26 => 'PEN',
        27 => 'COP',
        28 => 'ZAR',
        29 => 'HKD',
        30 => 'TWD',
        31 => 'SAR',
        32 => 'AED',
        33 => 'USD',
        34 => 'ARS',
        35 => 'ILS',
        36 => 'USD',
        37 => 'KZT',
        38 => 'KWD',
        39 => 'QAR',
        40 => 'CRC',
        41 => 'UYU',
        42 => 'USD',
        43 => 'USD',
        44 => 'USD',
        45 => 'USD',
        46 => 'USD',
        47 => 'USD',
    ];

    const CONDITIONS = [
        "(Factory New)" => "Factory New",
        "(Minimal Wear)" => "Minimal Wear",
        "(Field-Tested)" => "Field-Tested",
        "(Well-Worn)" => "Well-Worn",
        "(Battle-Scarred)" => "Battle-Scarred"
    ];

    const STICKERS_POS = [
        "AK-47 |" => [
            4 => 'Top',
            3 => 'Almost top',
            1 => 'Almost top',
            2 => 'Bad',
        ],
        "AUG |" => [
            4 => 'Top',
            3 => 'Top',
            2 => 'Top',
            1 => 'Bad'
        ],
        "AWP |" => [
            4 => 'Top',
            3 => 'Almost top',
            2 => 'Almost top',
            1 => 'Bad'
        ],
        "CZ75-Auto |" => [
            3 => 'Top',
            1 => 'Almost top',
            2 => 'Almost top',
            4 => 'Bad'
        ],
        "Desert Eagle |" => [
            2 => 'Top',
            3 => 'Almost top',
            4 => 'Almost top',
            1 => 'Bad'
        ],
        "Dual Berettas |" => [
            3 => 'Almost top',
            1 => 'Almost top',
            4 => 'Normal',
            2 => 'Normal',
        ],
        "FAMAS |" => [
            2 => 'Top',
            3 => 'Top',
            4 => 'Top',
            1 => 'Bad'
        ],
        "Five-SeveN |" => [
            1 => 'Top',
            3 => 'Almost top',
            2 => 'Almost top',
            4 => 'Bad'
        ],
        "G3SG1 |" => [
            5 => 'Top',
            4 => 'Almost top',
            3 => 'Almost top',
            2 => 'Bad',
            1 => 'Bad'
        ],
        "Galil AR |" => [
            2 => 'Top',
            3 => 'Almost top',
            1 => 'Almost top',
            4 => 'Bad',
        ],
        "Glock-18 |" => [
            1 => 'Top',
            3 => 'Almost top',
            2 => 'Almost top',
            4 => 'Bad'
        ],
        "M249 |" => [
            4 => 'Bad',
            3 => 'Bad',
            2 => 'Bad',
            1 => 'Bad'
        ],
        "M4A1-S |" => [
            2 => 'Top',
            1 => 'Almost top',
            3 => 'Almost top',
            4 => 'Bad'
        ],
        "M4A4 |" => [
            3 => 'Top',
            1 => 'Almost top',
            2 => 'Almost top',
            4 => 'Bad'
        ],
        "MAC-10 |" => [
            4 => 'Bad',
            1 => 'Bad',
            3 => 'Bad',
            2 => 'Bad'
        ],
        "MAG-7 |" => [
            4 => 'Bad',
            2 => 'Bad',
            3 => 'Bad',
            1 => 'Bad'
        ],
        "MP5-SD |" => [
            4 => 'Normal',
            2 => 'Normal',
            3 => 'Normal',
            1 => 'Normal'
        ],
        "MP7 |" => [
            1 => 'Bad',
            2 => 'Bad',
            3 => 'Bad',
            4 => 'Bad'
        ],
        "MP9 |" => [
            1 => 'Bad',
            3 => 'Bad',
            2 => 'Bad',
            4 => 'Bad'
        ],
        "Negev |" => [
            1 => 'Bad',
            2 => 'Bad',
            3 => 'Bad',
            4 => 'Bad'
        ],
        "Nova |" => [
            1 => 'Bad',
            2 => 'Bad',
            3 => 'Bad',
            4 => 'Bad'
        ],
        "P2000 |" => [
            1 => 'Top',
            2 => 'Almost top',
            3 => 'Almost top',
            4 => 'Bad'
        ],
        "P250 |" => [
            1 => 'Top',
            2 => 'Almost top',
            3 => 'Almost top',
            4 => 'Bad'
        ],
        "P90 |" => [
            1 => 'Bad',
            2 => 'Bad',
            3 => 'Bad',
            4 => 'Bad'
        ],
        "PP-Bizon |" => [
            1 => 'Bad',
            2 => 'Bad',
            3 => 'Bad',
            4 => 'Bad'
        ],
        "R8 Revolver |" => [
            1 => 'Normal',
            2 => 'Normal',
            3 => 'Normal',
            4 => 'Normal',
            5 => 'Normal'
        ],
        "Sawed-Off |" => [
            1 => 'Bad',
            2 => 'Bad',
            3 => 'Bad',
            4 => 'Bad'
        ],
        "SCAR-20 |" => [
            1 => 'Bad',
            2 => 'Bad',
            3 => 'Bad',
            4 => 'Bad'
        ],
        "SG 553 |" => [
            1 => 'Bad',
            2 => 'Bad',
            3 => 'Bad',
            4 => 'Bad'
        ],
        "SSG 08 |" => [
            2 => 'Almost top',
            4 => 'Almost top',
            3 => 'Almost top',
            1 => 'Normal'
        ],
        "Tec-9 |" => [
            1 => 'Top',
            2 => 'Almost top',
            3 => 'Almost top',
            4 => 'Bad'
        ],
        "UMP-45 |" => [
            4 => 'Bad',
            3 => 'Bad',
            2 => 'Bad',
            1 => 'Bad'
        ],
        "USP-S |" => [
            2 => 'Top',
            3 => 'Almost top',
            4 => 'Almost top',
            1 => 'Bad',
        ],
        "XM1014 |" => [
            1 => 'Bad',
            2 => 'Bad',
            3 => 'Bad',
            4 => 'Bad'
        ]
    ];
}
