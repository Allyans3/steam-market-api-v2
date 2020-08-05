<?php


namespace SteamApi\Responses;


use SteamApi\Interfaces\ResponseInterface;

class ItemNameId implements ResponseInterface
{
    const DELIMITER_START = 'Market_LoadOrderSpread(';
    const DELIMITER_END = ');';

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
            return "Not found item_nameId";
        }

        return $data;
    }
}