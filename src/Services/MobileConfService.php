<?php

namespace SteamApi\Services;

class MobileConfService
{
    public static function parseMaFile($file)
    {
        $fileContent = file_get_contents($file);

        if ($fileContent)
            return json_decode($fileContent, true);

        return false;
    }
}