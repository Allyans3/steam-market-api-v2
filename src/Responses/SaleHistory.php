<?php

namespace SteamApi\Responses;

use Carbon\Carbon;
use SteamApi\Interfaces\ResponseInterface;

class SaleHistory implements ResponseInterface
{
    const DELIMITER_START = 'var line1=';
    const DELIMITER_END = ';';

    private $data;

    public function __construct($response)
    {
        $this->data = $this->decodeResponse($response);
    }

    public function response()
    {
        return $this->data;
    }

    public function __destruct()
    {
        unset($this->data);
    }

    private function decodeResponse($response)
    {
        if (!is_array($response)) {
            $data = self::parseHistory($response);

            if (!$data || empty($data))
                return false;

            return $this->completeData($data);
        } else {
            $returnData = $response;

            $data = self::parseHistory($response['response']);

            if (!$data || empty($data)) {
                $returnData['response'] = false;
                return $returnData;
            }

            $returnData['response'] = $this->completeData($data);

            return $returnData;
        }
    }

    private function parseHistory($response)
    {
        $dataString = substr($response, strpos($response, self::DELIMITER_START) + strlen(self::DELIMITER_START));
        $dataString = substr($dataString, 0, strpos($dataString, self::DELIMITER_END));

        return json_decode($dataString, true);
    }

    private function completeData($data): array
    {
        return array_map(function ($item) {
            $datePieces = explode(' ', $item[0]);
            $date = date('Y-m-d', strtotime($datePieces[1] . ' ' . $datePieces[0] . ' ' . $datePieces[2]));
            $timestamp = Carbon::parse($date, 'Etc/GMT+0')->timestamp;

            return [
                'time' => $timestamp,
                'price' => $item[1],
                'volume' => (int) $item[2]
            ];
        }, $data);
    }
}