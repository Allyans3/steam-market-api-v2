<?php

namespace SteamApi\Interfaces;

interface PostRequestInterface
{
    public function getUrl();

    public function getHeaders();

    public function getFormData();

    public function call();

    public function getRequestMethod();
}