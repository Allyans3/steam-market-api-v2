<?php

namespace SteamApi\Engine;

use Curl\Curl;
use Curl\MultiCurl;
use RuntimeException;

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
        if (!isset($this->ch))
            $this->initCurl();

        curl_setopt_array($this->ch, $this->curlOpts + $proxy + [
                CURLOPT_CUSTOMREQUEST => $this->getRequestMethod(),
                CURLOPT_HTTPHEADER => self::mergeHeaders($this->getHeaders()),
                CURLOPT_HEADER => $detailed,
                CURLINFO_HEADER_OUT => true,
                CURLOPT_URL => $this->getUrl()
            ]
        );

        return $this->response($detailed ? $this->exec() : curl_exec($this->ch));
    }

    private function mergeHeaders($headers): array
    {
        $mergedHeaders = [];

        foreach ($headers as $key => $value) {
            $mergedHeaders[] = $key . ': ' . $value;
        }

        return $mergedHeaders;
    }

    public function steamMultiHttpRequest($proxyList, $detailed, $smartMulti)
    {
        $multiCurl = new MultiCurl();
        $responses['multi_list'] = [];

        foreach ($proxyList as $proxy) {
            $newCurl = new Curl();

            $newCurl->setUrl($this->getUrl());

            $newCurl->setHeaders($this->getHeaders());
            $newCurl->setProxy($proxy['ip'], $proxy['port']);
            $newCurl->setProxyType($proxy['type']);
            $newCurl->setTimeout($proxy['timeout']);
            $newCurl->setConnectTimeout($proxy['connect_timeout']);
            $newCurl->setUserAgent($proxy['user_agent']);
            $newCurl->setProxyTunnel();

            $multiCurl->addCurl($newCurl);
        }

        $multiCurl->success(function ($instance) use (&$responses) {
            $responses['multi_list'][] = json_decode(json_encode($instance->response), true);
        });

//        $timeBefore = Carbon::now();

//        $multiCurl->success(function ($instance) use (&$responses, $multiCurl, $timeBefore, $detailed, $smartMulti) {
//            $respInfo = json_decode(json_encode($instance->response), true);
//
////            $delay = Carbon::now()->diffInMilliseconds($timeBefore);
//
//            $responses['multi_list'][] = $respInfo;
//
////            $responses['multi_list'][] = !$detailed ? $respInfo : [
////                'request_headers' => self::getRequestHeaders($instance),
////                'headers' => self::getHeadersFromCurlResponse($instance->rawResponseHeaders),
////                'response' => $respInfo,
////                'remote_ip' => $instance->getOpt(CURLOPT_PROXY),
////                'code' => $instance->errorCode ?: $instance->httpStatusCode,
////                'url' => $instance->url,
////                'total_time' => $delay,
////            ];
//
//            if ($smartMulti)
//                $multiCurl->stop();
//        });

//        $multiCurl->error(function ($instance) use(&$responses, $multiCurl) {
//            $responses['errors'][] = [
//                'ip' => $instance->getOpt(CURLOPT_PROXY),
//                'message' => $instance->errorMessage,
//                'code' => $instance->httpStatusCode,
//                'errorCode' => $instance->errorCode,
//            ];
//        });

        $multiCurl->start();

        $multiCurl->close();

        unset($multiCurl);

//        $responses['time'] = Carbon::now()->diffInMilliseconds($timeBefore);
        return $this->response($responses);
    }

    public function exec()
    {
        $response = curl_exec($this->ch);
        $error = curl_error($this->ch);

        $request_headers = curl_getinfo($this->ch,CURLINFO_HEADER_OUT);
        $header_size = curl_getinfo($this->ch,CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);

        $result = [
            'request_headers' => self::getHeadersFromCurlResponse($request_headers),
            'headers' => self::getHeadersFromCurlResponse($header),
            'response' => substr($response, $header_size),
            'error' => '',
            'remote_ip' => curl_getinfo($this->ch,CURLINFO_PRIMARY_IP),
            'code' => '',
            'url' => curl_getinfo($this->ch,CURLINFO_EFFECTIVE_URL),
            'total_time' => bcdiv(curl_getinfo($this->ch,CURLINFO_TOTAL_TIME_T), 1000)
        ];

        if ($error !== "") {
            $result['error'] = $error;
            return $result;
        }

        $result['code'] = curl_getinfo($this->ch,CURLINFO_HTTP_CODE);

        return $result;
    }

    private function getRequestHeaders($instance)
    {
        $requestHeaders = [];

        foreach ($instance->requestHeaders as $key => $value) {
            $requestHeaders[$key] = $value;
        }

        return $requestHeaders;
    }

    private function getHeadersFromCurlResponse($response): array
    {
        $headers = [];

        if (!$response)
            return $headers;

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
        if ($this->ch)
            curl_close($this->ch);

        $class = self::RESPONSE_PREFIX . strrev(explode('\\', strrev(get_called_class()), 2)[0]);

        if (!class_exists($class)) {
            throw new RuntimeException('Call to undefined response type');
        }

        return new $class($data);
    }
}
