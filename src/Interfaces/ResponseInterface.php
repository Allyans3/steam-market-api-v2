<?php

namespace SteamApi\Interfaces;

interface ResponseInterface
{
    public function __construct($response);

    public function response();

    public function raw();
}