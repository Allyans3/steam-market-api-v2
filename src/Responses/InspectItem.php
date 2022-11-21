<?php

namespace SteamApi\Responses;

use SteamApi\Interfaces\ResponseInterface;
use SteamApi\Services\ResponseService;

class InspectItem implements ResponseInterface
{
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
                $data = json_decode($returnData['response'], true);

                if (!$data)
                    $returnData['response'] = false;
                else
                    $returnData['response'] = self::completeData($data);

                return $returnData;
            } else {
                $data = json_decode($returnData, true);

                if (!$data)
                    return false;

                return self::completeData($data);
            }
        }
    }

    /**
     * @param $data
     * @return array
     */
    private function completeData($data): array
    {
        return ResponseService::filterData($data, $this->select, $this->makeHidden);
    }
}