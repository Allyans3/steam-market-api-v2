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
        if (!is_array($response)) {
            $data = self::completeData($response);

            if (!$data || empty($data))
                return false;

            return $data;
        } else {
            $returnData = $response;

            $data = self::completeData($response['response']);

            if (!$data || empty($data)) {
                $returnData['response'] = false;
                return $returnData;
            }

            $returnData['response'] = $data;

            return $returnData;
        }
    }

    private function completeData($response)
    {
        $dataString = substr($response, strpos($response, self::DELIMITER_START) + strlen(self::DELIMITER_START));
        $dataString = substr($dataString, 0, strpos($dataString, self::DELIMITER_END));

        return json_decode($dataString);
    }
}