<?php

namespace SteamApi\Responses\Steam;

use SteamApi\Interfaces\ResponseInterface;

class ItemNameId implements ResponseInterface
{
    const DELIMITER_START = 'Market_LoadOrderSpread(';
    const DELIMITER_END = ');';

    private $response;
    private $detailed;
    private $multiRequest;

    /**
     * @param $response
     * @param bool $detailed
     * @param bool $multiRequest
     */
    public function __construct($response, bool $detailed = false, bool $multiRequest = false)
    {
        $this->response = $response;
        $this->detailed = $detailed;
        $this->multiRequest = $multiRequest;
    }

    /**
     * Destructor
     */
    public function __destruct()
    {
        unset($this->response);
        unset($this->detailed);
        unset($this->multiRequest);
    }

    /**
     * @return false|mixed
     */
    public function response()
    {
        return $this->decodeResponse($this->response);
    }

    /**
     * @param $response
     * @return false|mixed
     */
    public function decodeResponse($response)
    {
        if ($this->multiRequest) {
            // TODO
            return false;
        } else {
            $returnData = $response;

            if ($this->detailed) {
                $data = self::completeData($returnData['response']);

                if (!$data)
                    $returnData['response'] = false;
                else
                    $returnData['response'] = $data;

                return $returnData;
            } else {
                $data = self::completeData($returnData);

                if (!$data)
                    return false;

                return $data;
            }
        }
    }

    /**
     * @param $response
     * @return mixed
     */
    private function completeData($response)
    {
        $dataString = substr($response, strpos($response, self::DELIMITER_START) + strlen(self::DELIMITER_START));
        $dataString = substr($dataString, 0, strpos($dataString, self::DELIMITER_END));

        return json_decode($dataString);
    }
}