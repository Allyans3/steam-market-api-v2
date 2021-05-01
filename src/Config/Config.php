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
        9 => 'SEK',
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
        34 => 'ARS',
        35 => 'ILS',
        37 => 'KZT',
        38 => 'KWD',
        39 => 'QAR',
        40 => 'CRC',
        41 => 'UYU',
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
            2 => 'Normal',
            1 => 'Bad'
        ],
        "AUG |" => [
            4 => 'Top',
            3 => 'Almost top',
            2 => 'Normal',
            1 => 'Bad'
        ],
        "AWP |" => [
            4 => 'Top',
            3 => 'Almost top',
            2 => 'Normal',
            1 => 'Bad'
        ],
        "CZ75-Auto |" => [
            1 => 'Top',
            3 => 'Top',
            2 => 'Almost top',
            4 => 'Bad'
        ],
        "Desert Eagle |" => [
            2 => 'Top',
            3 => 'Top',
            4 => 'Top',
            1 => 'Bad'
        ],
        "Dual Berettas |" => [
            4 => 'Top',
            2 => 'Top',
            3 => 'Almost top',
            1 => 'Almost top'
        ],
        "FAMAS |" => [
            2 => 'Top',
            3 => 'Almost top',
            4 => 'Normal',
            1 => 'Bad'
        ],
        "Five-SeveN |" => [
            1 => 'Top',
            3 => 'Top',
            2 => 'Almost top',
            4 => 'Bad'
        ],
        "G3SG1 |" => [
            5 => 'Top',
            4 => 'Almost top',
            3 => 'Normal',
            2 => 'Bad',
            1 => 'Bad'
        ],
        "Galil AR |" => [
            2 => 'Top',
            3 => 'Almost top',
            1 => 'Normal',
            4 => 'Bad',
        ],
        "Glock-18 |" => [
            1 => 'Top',
            3 => 'Top',
            2 => 'Almost top',
            4 => 'Bad'
        ],
        "M249 |" => [
            4 => 'Top',
            3 => 'Normal',
            2 => 'Normal',
            1 => 'Normal'
        ],
        "M4A1-S |" => [
            2 => 'Top',
            3 => 'Almost top',
            4 => 'Almost top',
            1 => 'Bad'
        ],
        "M4A4 |" => [
            2 => 'Top',
            3 => 'Almost top',
            4 => 'Almost top',
            1 => 'Bad'
        ],
        "MAC-10 |" => [
            4 => 'Top',
            1 => 'Top',
            3 => 'Almost top',
            2 => 'Bad'
        ],
        "MAG-7 |" => [
            4 => 'Top',
            2 => 'Top',
            3 => 'Almost top',
            1 => 'Bad'
        ],
        "MP5-SD |" => [
            4 => 'Top',
            2 => 'Top',
            3 => 'Almost top',
            1 => 'Bad'
        ],
        "MP7 |" => [
            2 => 'Top',
            1 => 'Almost top',
            3 => 'Bad',
            4 => 'Bad'
        ],
        "MP9 |" => [
            1 => 'Top',
            3 => 'Top',
            2 => 'Almost top',
            4 => 'Bad'
        ],
        "Negev |" => [
            2 => 'Top',
            1 => 'Almost top',
            4 => 'Normal',
            3 => 'Bad'
        ],
        "Nova |" => [
            4 => 'Top',
            1 => 'Top',
            3 => 'Almost top',
            2 => 'Almost top'
        ],
        "P2000 |" => [
            1 => 'Top',
            3 => 'Top',
            2 => 'Almost top',
            4 => 'Bad'
        ],
        "P250 |" => [
            1 => 'Top',
            3 => 'Top',
            2 => 'Almost top',
            4 => 'Bad'
        ],
        "P90 |" => [
            2 => 'Top',
            4 => 'Normal',
            3 => 'Bad',
            1 => 'Bad'
        ],
        "PP-Bizon |" => [
            1 => 'Top',
            3 => 'Top',
            5 => 'Top',
            2 => 'Almost top'
        ],
        "R8 Revolver |" => [
            3 => 'Top',
            5 => 'Top',
            1 => 'Almost top',
            2 => 'Normal',
            4 => 'Bad'
        ],
        "Sawed-Off |" => [
            1 => 'Top',
            3 => 'Top',
            2 => 'Almost top',
            4 => 'Bad'
        ],
        "SCAR-20 |" => [
            4 => 'Top',
            2 => 'Almost top',
            3 => 'Normal',
            1 => 'Bad'
        ],
        "SG 553 |" => [
            1 => 'Top',
            3 => 'Top',
            2 => 'Almost top',
            4 => 'Normal'
        ],
        "SSG 08 |" => [
            2 => 'Top',
            4 => 'Top',
            3 => 'Almost top',
            1 => 'Normal'
        ],
        "Tec-9 |" => [
            1 => 'Top',
            3 => 'Top',
            4 => 'Top',
            2 => 'Almost top'
        ],
        "UMP-45 |" => [
            4 => 'Top',
            3 => 'Almost top',
            2 => 'Almost top',
            1 => 'Bad'
        ],
        "USP-S |" => [
            4 => 'Top',
            2 => 'Top',
            3 => 'Almost top',
            1 => 'Bad',
        ],
        "XM1014 |" => [
            1 => 'Top',
            2 => 'Top',
            3 => 'Top',
            4 => 'Top'
        ]
    ];
}
