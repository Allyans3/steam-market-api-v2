<?php

namespace SteamApi\Responses;

use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use SteamApi\Interfaces\ResponseInterface;
use SteamApi\Services\MixedService;
use SteamApi\Services\ResponseService;

class ItemOrdersHistogram implements ResponseInterface
{
    const DELIMITER_START = '<span class="market_commodity_orders_header_promote">';
    const DELIMITER_END = '</span>';

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
     * @throws InvalidSelectorException
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
     * @throws InvalidSelectorException
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

                if (!$data || !array_key_exists('success', $data) || $data['success'] !== 1)
                    $returnData['response'] = false;
                else
                    $returnData['response'] = self::completeData($data);

                return $returnData;
            } else {
                $data = json_decode($returnData, true);

                if (!$data || !array_key_exists('success', $data) || $data['success'] !== 1)
                    return false;

                return self::completeData($data);
            }
        }
    }

    /**
     * @param $price
     * @return float|int
     */
    private function toFloat($price)
    {
        return +bcdiv($price, 100, 2);
    }

    /**
     * @param $data
     * @return int|mixed
     */
    private function parseOrderSummary($data)
    {
        $dataString = substr($data, strpos($data, self::DELIMITER_START) + strlen(self::DELIMITER_START));
        $dataString = substr($dataString, 0, strpos($dataString, self::DELIMITER_END));

        $data = json_decode($dataString);

        if (!$data || empty($data))
            return 0;

        return $data;
    }

    /**
     * @param $data
     * @return array
     */
    private function setFields($data): array
    {
        $returnData = [];

        $volume = 0;

        foreach ($data as $order) {

            $returnData[] = [
                'price' => $order[0],
                'volume' => (int) bcsub($order[1], $volume),
                'description' => $order[2],
            ];

            $volume = $order[1];
        }

        return $returnData;
    }

    /**
     * @param $data
     * @return array
     * @throws InvalidSelectorException
     */
    private function parseOrderTable($data): array
    {
        $prices = [];

        if ($data) {
            $document = new Document($data);
            $table = $document->find('table')[0];

            foreach ($table->find('tr') as $row) {
                if ($row->has('td'))
                    $prices[] = [
                        'price' => MixedService::toFloat($row->find('td')[0]->text()),
                        'price_text' => $row->find('td')[0]->text(),
                        'count' => +$row->find('td')[1]->text()
                    ];
            }
        }

        return $prices;
    }

    /**
     * @param $data
     * @return array
     * @throws InvalidSelectorException
     */
    private function completeData($data): array
    {
        return ResponseService::filterData(
            [
                'highest_buy_order' => self::toFloat($data['highest_buy_order']),
                'lowest_sell_order' => self::toFloat($data['lowest_sell_order']),
                'buy_order_summary' => self::parseOrderSummary($data['buy_order_summary']),
                'sell_order_summary' => self::parseOrderSummary($data['sell_order_summary']),
                'buy_order_graph' =>  self::setFields($data['buy_order_graph']),
                'sell_order_graph' => self::setFields($data['sell_order_graph']),
                'buy_order_table' => self::parseOrderTable($data['buy_order_table']),
                'sell_order_table' => self::parseOrderTable($data['sell_order_table']),
                'graph_max_y' => $data['graph_max_y'],
                'graph_min_x' => $data['graph_min_x'],
                'graph_max_x' => $data['graph_max_x'],
                'price_prefix' => $data['price_prefix'],
                'price_suffix' => $data['price_suffix']
            ],
            $this->select, $this->makeHidden);
    }
}