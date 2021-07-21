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
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
    ];

    public function initCurl()
    {
        return $this->ch = curl_init();
    }

    public function steamHttpRequest($proxy = [], $detailed = false)
    {
        if (!isset($this->ch)) {
            $this->initCurl();
        }

        curl_setopt_array($this->ch, $this->curlOpts + $proxy + [
                CURLOPT_CUSTOMREQUEST => $this->getRequestMethod(),
                CURLOPT_URL => $this->getUrl(),
                CURLOPT_HEADER => $detailed,
                CURLINFO_HEADER_OUT => true
            ]
        );

        return $this->response($detailed ? $this->exec() : curl_exec($this->ch));
    }

    public function exec()
    {
        $response = curl_exec($this->ch);
        $error = curl_error($this->ch);
        $result = [
            'request_headers' => '',
            'headers' => '',
            'response' => '',
            'error' => '',
            'remote_ip' => '',
            'code' => '',
            'url' => '',
            'total_time' => ''
        ];

        if ($error !== "") {
            $result['error'] = $error;
            return $result;
        }

        $request_headers = curl_getinfo($this->ch,CURLINFO_HEADER_OUT);
        $header_size = curl_getinfo($this->ch,CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);

        $result['request_headers'] = $this->get_headers_from_curl_response($request_headers);
        $result['headers'] = $this->get_headers_from_curl_response($header);
        $result['response'] = substr($response, $header_size);
        $result['remote_ip'] = curl_getinfo($this->ch,CURLINFO_PRIMARY_IP);
        $result['code'] = curl_getinfo($this->ch,CURLINFO_HTTP_CODE);
        $result['url'] = curl_getinfo($this->ch,CURLINFO_EFFECTIVE_URL);
        $result['total_time'] = bcdiv(curl_getinfo($this->ch,CURLINFO_TOTAL_TIME_T), 1000);

        return $result;
    }

    private function get_headers_from_curl_response($response): array
    {
        $headers = [];

        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

        foreach (explode("\r\n", $header_text) as $i => $line)
            if ($i === 0)
                $headers['http_code'] = $line;
            else {
                list ($key, $value) = explode(': ', $line);
                $headers[$key] = trim($value);
            }

        return $headers;
    }

    public function response($data)
    {
        curl_close($this->ch);

        $class = self::RESPONSE_PREFIX . strrev(explode('\\', strrev(get_called_class()), 2)[0]);

        if (!class_exists($class)) {
            throw new RuntimeException('Call to undefined response type');
        }

        return new $class($data);
    }
}
