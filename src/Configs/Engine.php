<?php

namespace SteamApi\Configs;

class Engine
{
    const REQUEST_PREFIX = '\\SteamApi\\Requests\\';

    const RESPONSE_PREFIX = '\\SteamApi\\Responses\\';

    const HTTP_CODES = [
        // Successful 2xx
        200 => "OK",
        201 => "Created",
        202 => "Accepted",
        203 => "Non-Authoritative Information",
        204 => "No Content",
        205 => "Reset Content",
        206 => "Partial Content",
        207 => "Multi-Status (WebDAV)",
        208 => "Already Reported (WebDAV)",
        226 => "IM Used",

        // Redirection 3xx
        300 => "Multiple Choices",
        301 => "Moved Permanently",
        302 => "Found",
        303 => "See Other",
        304 => "Not Modified",
        305 => "Use Proxy",
        306 => "(Unused)",
        307 => "Temporary Redirect",
        308 => "Permanent Redirect",

        // Client Error 4xx
        400 => "Bad Request",
        401 => "Unauthorized",
        402 => "Payment Required",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        406 => "Not Acceptable",
        407 => "Proxy Authentication Required",
        408 => "Request Timeout",
        409 => "Conflict",
        410 => "Gone",
        411 => "Length Required",
        412 => "Precondition Failed",
        413 => "Content Too Large",
        414 => "URI Too Long",
        415 => "Unsupported Media Type",
        416 => "Range Not Satisfiable",
        417 => "Expectation Failed",
        418 => "(Unused)",
        420 => "Enhance Your Calm (Twitter)",
        421 => "Misdirected Request",
        422 => "Unprocessable Content",
        423 => "Locked (WebDAV)",
        424 => "Failed Dependency (WebDAV)",
        425 => "Reserved for WebDAV",
        426 => "Upgrade Required",
        428 => "Precondition Required",
        429 => "Too Many Requests",
        431 => "Request Header Fields Too Large",
        444 => "No Response (Nginx)",
        449 => "Retry With (Microsoft)",
        450 => "Blocked by Windows Parental Controls (Microsoft)",
        451 => "Unavailable For Legal Reasons",
        499 => "Client Closed Request (Nginx)",

        // Server Error 5xx
        500 => "Internal Server Error",
        501 => "Not Implemented",
        502 => "Bad Gateway",
        503 => "Service Unavailable",
        504 => "Gateway Timeout",
        505 => "HTTP Version Not Supported",
        506 => "Variant Also Negotiates (Experimental)",
        507 => "Insufficient Storage (WebDAV)",
        508 => "Loop Detected (WebDAV)",
        509 => "Bandwidth Limit Exceeded (Apache)",
        510 => "Not Extended",
        511 => "Network Authentication Required",
        598 => "Network read timeout error",
        599 => "Network connect timeout error"
    ];
}