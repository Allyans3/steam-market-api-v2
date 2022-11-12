<?php

namespace SteamApi\Responses;

use Carbon\Carbon;
use SteamApi\Interfaces\ResponseInterface;
use SteamApi\Services\ResponseService;

class SaleHistory implements ResponseInterface
{
    const DELIMITER_START = 'var line1=';
    const DELIMITER_END = ';';

    private $response;
    private $detailed;
    private $multiRequest;

    private $select;
    private $makeHidden;

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

        unset($this->select);
        unset($this->makeHidden);
    }

    /**
     * @param array $select
     * @param array $makeHidden
     * @return array|false
     */
    public function response(array $select = [], array $makeHidden = [])
    {
        $this->select = $select;
        $this->makeHidden = $makeHidden;

        return $this->decodeResponse($this->response);
    }

    /**
     * @param $response
     * @return array|false
     */
    public function decodeResponse($response)
    {
        if ($this->multiRequest) {
            // TODO
            return false;
        } else {
            $returnData = $response;

            if ($this->detailed) {
                $data = self::parseHistory($returnData['response']);

                if (!$data || empty($data))
                    $returnData['response'] = false;
                else
                    $returnData['response'] = self::completeData($data);

                return $returnData;
            } else {
                $data = self::parseHistory($returnData);

                if (!$data || empty($data))
                    return false;

                return self::completeData($data);
            }
        }
    }

    /**
     * @param $response
     * @return mixed
     */
    private function parseHistory($response)
    {
        $dataString = substr($response, strpos($response, self::DELIMITER_START) + strlen(self::DELIMITER_START));
        $dataString = substr($dataString, 0, strpos($dataString, self::DELIMITER_END));

        return json_decode($dataString, true);
    }

    /**
     * @param $data
     * @return array
     */
    private function completeData($data): array
    {
        return array_map(function ($item) {
            $datePieces = explode(' ', $item[0]);
            $date = date('Y-m-d', strtotime($datePieces[1] . ' ' . $datePieces[0] . ' ' . $datePieces[2]));
            $timestamp = Carbon::parse($date, 'Etc/GMT+0')->timestamp;

            $timeData = [
                'time' => $timestamp,
                'price' => $item[1],
                'volume' => (int) $item[2]
            ];

            return ResponseService::filterData($timeData, $this->select, $this->makeHidden);
        }, $data);
    }
}