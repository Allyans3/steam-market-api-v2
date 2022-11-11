<?php

namespace SteamApiOld\Interfaces;

interface ResponseInterface
{
    public function __construct($response);

    public function response();
}