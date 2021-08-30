<?php


namespace SteamApi\Responses;


use SteamApi\Interfaces\ResponseInterface;

class ItemOrdersHistogram implements ResponseInterface
{
    const DELIMITER_START = '<span class="market_commodity_orders_header_promote">';
    const DELIMITER_END = '</span>';

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
            $data = json_decode($response, true);

            if (!$data)
                return false;

            return $this->completeData($data);
        } else {
            $returnData = $response;

            $data = json_decode($response['response'], true);

            if (!$data) {
                $returnData['response'] = false;
                return $returnData;
            }

            $returnData['response'] = $this->completeData($data);

            return $returnData;
        }
    }

    private function setFields($data)
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

    private function parseOrderSummary($data)
    {
        $dataString = substr($data, strpos($data, self::DELIMITER_START) + strlen(self::DELIMITER_START));
        $dataString = substr($dataString, 0, strpos($dataString, self::DELIMITER_END));

        $data = json_decode($dataString);

        if (!$data || empty($data)) {
            return "Not found";
        }

        return $data;
    }

    private function completeData($data): array
    {
        return [
            'highest_buy_order' => (int) $data['highest_buy_order'],
            'lowest_sell_order' => (int) $data['lowest_sell_order'],
            'buy_order_summary' => $this->parseOrderSummary($data['buy_order_summary']),
            'sell_order_summary' => $this->parseOrderSummary($data['sell_order_summary']),
            'buy_order_graph' =>  $this->setFields($data['buy_order_graph']),
            'sell_order_graph' => $this->setFields($data['sell_order_graph']),
            'graph_max_y' => $data['graph_max_y'],
            'graph_min_x' => $data['graph_min_x'],
            'graph_max_x' => $data['graph_max_x'],
            'price_prefix' => $data['price_prefix'],
            'price_suffix' => $data['price_suffix']
        ];
    }
}