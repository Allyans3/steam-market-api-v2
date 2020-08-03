<?php

namespace SteamApi\Engine;

use Psy\Exception\RuntimeException;

abstract class Request
{
    const RESPONSE_PREFIX = '\\SteamApi\\Responses\\';

    private $ch;
    private $curlOpts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
    ];

    public function initCurl()
    {
        return $this->ch = curl_init();
    }

    public function closeCurl()
    {
        return curl_close($this->ch);
    }

    public function steamHttpRequest()
    {
        if (!isset($this->ch)) {
            $this->initCurl();
        }

        curl_setopt_array($this->ch, $this->curlOpts + [
                CURLOPT_CUSTOMREQUEST => $this->getRequestMethod(),
                CURLOPT_URL => $this->getUrl()
            ]);

        return $this->response(curl_exec($this->ch));
    }

    public function response($data)
    {
        $this->closeCurl();

        $class = self::RESPONSE_PREFIX . strrev(explode('\\', strrev(get_called_class()), 2)[0]);

        if (!class_exists($class)) {
            throw new RuntimeException('Call to undefined response type');
        }

        return new $class($data);
    }
}
