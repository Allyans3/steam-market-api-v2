<?php

namespace SteamApi\Interfaces;

interface ResponseInterface
{
    public function __construct($response, bool $detailed = false, bool $multiRequest = false);

    public function __destruct();

    public function response();

    public function decodeResponse($response);
}