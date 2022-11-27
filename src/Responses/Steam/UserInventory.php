<?php

namespace SteamApi\Responses\Steam;

use Curl\MultiCurl;
use ErrorException;
use SteamApi\Interfaces\ResponseInterface;
use SteamApi\Services\ResponseService;

class UserInventory implements ResponseInterface
{
    private $response;
    private $detailed;
    private $multiRequest;

    private $select;
    private $makeHidden;
    private $withInspectData;
    private $steamId;

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
        unset($this->withInspectData);
        unset($this->steamId);
    }

    /**
     * @param array $select
     * @param array $makeHidden
     * @param bool $withInspectData
     * @param int|null $steamId
     * @return array|false
     * @throws ErrorException
     */
    public function response(array $select = [], array $makeHidden = [], bool $withInspectData = false, int $steamId = null)
    {
        $this->select = $select;
        $this->makeHidden = $makeHidden;
        $this->withInspectData = $withInspectData;
        $this->steamId = $steamId;

        return $this->decodeResponse($this->response);
    }

    /**
     * @param $response
     * @return array|false
     * @throws ErrorException
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
     * @throws ErrorException
     */
    private function completeData($data): array
    {
        $returnData = $data;

        if ($this->withInspectData)
            $returnData = self::parseInventory($data);

        return ResponseService::filterData($returnData, $this->select, $this->makeHidden);
    }

    /**
     * @param $inventory
     * @return mixed
     * @throws ErrorException
     */
    private function parseInventory($inventory)
    {
        $multiCurl = new MultiCurl();
        $multiCurl->setConcurrency(75);
        $multiCurl->setHeader('Origin', 'chrome-extension://jjicbefpemnphinccgikpdaagjebbnhg');

        $inspectItems = [];

        foreach ($inventory['descriptions'] as $description) {
            foreach ($inventory['assets'] as $asset) {
                if ($asset['classid'] === $description['classid'] &&
                    $asset['instanceid'] === $description['instanceid']&&
                    array_key_exists('actions', $description))
                {
                    $inspectLink = str_replace("%assetid%", $asset['assetid'], $description['actions'][0]['link']);
                    $inspectLink = str_replace("%owner_steamid%", $this->steamId, $inspectLink);

                    $multiCurl->addGet('https://api.csgofloat.com/', array(
                        'url' => $inspectLink
                    ));

                    break;
                }
            }
        }

        $multiCurl->success(function($instance) use(&$inspectItems) {
            $parts = parse_url($instance->url);
            parse_str($parts['query'], $query);

            $itemInfo = json_decode(json_encode($instance->response), true);

            if (array_key_exists('iteminfo', $itemInfo))
                $inspectItems[] = $itemInfo['iteminfo'];
        });

        $multiCurl->start();

        $inventory['inspect_items'] = $inspectItems;

        return $inventory;
    }
}