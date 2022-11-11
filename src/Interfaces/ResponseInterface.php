<?php

namespace SteamApi\Interfaces;

interface ResponseInterface
{
    public function __construct($response, bool $detailed = false, bool $multiRequest = false);

    public function __destruct();

    public function response(array $select = [], array $makeHidden = []);

    public function decodeResponse($response);
}