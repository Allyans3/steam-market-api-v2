<?php

namespace SteamApi\Engine;

use SteamApi\Configs\Engine;
use SteamApi\Exception\InvalidClassException;
use SteamApi\Services\EngineService;

abstract class Request
{
    private $curl;

    private $defaultCurlOpts = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLINFO_HEADER_OUT => true,
    ];

    /**
     * @param array $proxy
     * @param string $cookies
     * @param false $detailed
     * @param false $multiRequest
     * @param array $curlOpts
     * @return mixed|void
     * @throws InvalidClassException
     */
    public function makeRequest(array $proxy = [], string $cookies = '', bool $detailed = false, array $curlOpts = [],
                                bool $multiRequest = false)
    {
//        if ($multiRequest)
//            return self::makeMultiRequest($proxy, $detailed);

        return self::makeSingleRequest($proxy, $cookies, $detailed, $curlOpts);
    }

    /**
     * @param array $proxy
     * @param string $cookies
     * @param false $detailed
     * @param array $curlOpts
     * @return mixed
     * @throws InvalidClassException
     */
    private function makeSingleRequest(array $proxy = [], string $cookies = '', bool $detailed = false, array $curlOpts = [])
    {
        if (!isset($this->curl))
            $this->curl = curl_init();

        $requestMethod = $this->getRequestMethod();

        curl_setopt_array($this->curl,
            $this->defaultCurlOpts + EngineService::setProxyForSingle($proxy) + $curlOpts + [
                CURLOPT_CUSTOMREQUEST => $requestMethod,
                CURLOPT_HTTPHEADER => self::mergeHeaders($this->getHeaders()),
                CURLOPT_URL => $this->getUrl(),
                CURLOPT_HEADER => $detailed,
                CURLOPT_COOKIE => $cookies,
                CURLOPT_POSTFIELDS => ($requestMethod === 'POST') ? http_build_query($this->getFormData()) : ""
            ]
        );

        return $this->response($detailed ? self::exec() : curl_exec($this->curl), $detailed);
    }

    /**
     * @return array
     */
    public function exec(): array
    {
        $response = curl_exec($this->curl);

        $requestHeaders = curl_getinfo($this->curl,CURLINFO_HEADER_OUT);
        $headerSize = curl_getinfo($this->curl,CURLINFO_HEADER_SIZE);
        $responseHeader = substr($response, 0, $headerSize);

        $code = curl_getinfo($this->curl,CURLINFO_HTTP_CODE) ?: '';
        $messageCode = array_key_exists($code, Engine::HTTP_CODES) ? Engine::HTTP_CODES[$code] : '';

        return [
            'request_headers' => self::headersToArray($requestHeaders),
            'response_headers' => self::headersToArray($responseHeader),
            'url' => curl_getinfo($this->curl,CURLINFO_EFFECTIVE_URL),
            'code' => $code,
            'message' => $messageCode,
            'error' => curl_error($this->curl),
            'cookies' => self::getCookie($response),
            'remote_ip' => curl_getinfo($this->curl,CURLINFO_PRIMARY_IP),
            'local_ip' => curl_getinfo($this->curl,CURLINFO_LOCAL_IP),
            'total_time' => bcdiv(curl_getinfo($this->curl,CURLINFO_TOTAL_TIME_T), 1000),
            'response' => substr($response, $headerSize)
        ];
    }

    /**
     * @param $response
     * @return array
     */
    private function getCookie($response): array
    {
        preg_match_all('/^Set-Cookie:\s*([^;]*)/mi',
            $response, $match_found);

        $cookies = [];

        foreach ($match_found[1] as $item) {
            parse_str($item, $cookie);
            $cookies = array_merge($cookies, $cookie);
        }

        return $cookies;
    }

    /**
     * @param string $header
     * @return array
     */
    private function headersToArray(string $header): array
    {
        $headers = [];
        $headersTmpArray = explode("\r\n", $header);

        for ($i = 0 ; $i < count( $headersTmpArray ) ; ++$i) {
            // we don't care about the two \r\n lines at the end of the headers
            if (strlen( $headersTmpArray[$i] ) > 0) {

                // the headers start with HTTP status codes, which do not contain a colon, so we can filter them out too
                if (strpos( $headersTmpArray[$i] , ":")) {
                    $headerName = substr( $headersTmpArray[$i] , 0 , strpos( $headersTmpArray[$i] , ":" ) );
                    $headerValue = substr( $headersTmpArray[$i] , strpos( $headersTmpArray[$i] , ":" )+1 );
                    $headers[$headerName] = trim($headerValue);
                }
            }
        }

        return $headers;
    }

//    private function makeMultiRequest(array $proxy = [], bool $detailed = false)
//    {
//
//    }

    /**
     * @param $headers
     * @return array
     */
    private function mergeHeaders($headers): array
    {
        $mergedHeaders = [];

        foreach ($headers as $key => $value) {
            $mergedHeaders[] = $key . ': ' . $value;
        }

        return $mergedHeaders;
    }

    /**
     * @throws InvalidClassException
     */
    public function response($data, bool $detailed = false, bool $multiRequest = false)
    {
        $class = self::getResponseClass();

        if (!class_exists($class))
            throw new InvalidClassException('Call to undefined Response Class');

        return new $class($data, $detailed, $multiRequest);
    }

    /**
     * @return string
     */
    private function getResponseClass(): string
    {
        $reversedArr = explode('\\', strrev(get_called_class()));

        $method = strrev($reversedArr[0]);
        $folders = '';

        unset($reversedArr[0]);

        foreach ($reversedArr as $folder) {
            if (strrev($folder) === "Requests")
                break;

            $folders = strrev($folder) . '\\' . $folders;
        }

        return Engine::RESPONSE_PREFIX . $folders . $method;
    }

    /**
     * Destructor of Request Engine
     */
    function __destruct() {
        if ($this->curl)
            curl_close($this->curl);
    }
}