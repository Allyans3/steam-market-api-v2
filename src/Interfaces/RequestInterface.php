<?php

namespace SteamApi\Interfaces;

interface RequestInterface
{
    public function __construct($appId, $options = []);

    public function getUrl();

    public function call($options = []);

    public function getRequestMethod();
}