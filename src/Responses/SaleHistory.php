<?php

namespace SteamApi\Responses;

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

    private function decodeResponse($response)
    {
        $dataString = substr($response, strpos($response, self::DELIMITER_START) + strlen(self::DELIMITER_START));
        $dataString = substr($dataString, 0, strpos($dataString, self::DELIMITER_END));

        $data = json_decode($dataString);

        if (!$data || empty($data)) {
            return [];
        }

        return $this->completeData($data);
    }

    private function completeData($data)
    {
        return array_map(function ($item) {
            $date = explode(' ', $item[0]);
            return [
                'sale_date' => date('Y-m-d', strtotime($date[1] . ' ' . $date[0] . ' ' . $date[2])),
                'sale_price' => $item[1],
                'quantity' => (int) $item[2]
            ];
        }, $data);
    }
}