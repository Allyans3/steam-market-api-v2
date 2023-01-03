<?php

namespace SteamApi\Traits;

use SteamApi\Exception\InvalidClassException;

trait TradeOfferMethods
{
    /**
     * @return mixed
     * @throws InvalidClassException
     */
    public function getIncomingOffers(array $options = [])
    {
        $class = self::getClass('IncomingOffers', 'TradeOffers');

        return (new $class($options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @return mixed
     * @throws InvalidClassException
     */
    public function getSentOffers(array $options = [])
    {
        $class = self::getClass('SentOffers', 'TradeOffers');

        return (new $class($options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @return mixed
     * @throws InvalidClassException
     */
    public function getTradeOffer($tradeOfferId)
    {
        $class = self::getClass('TradeOffer', 'TradeOffers');

        return (new $class($tradeOfferId))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @return mixed
     * @throws InvalidClassException
     */
    public function getTradeLink()
    {
        $class = self::getClass('TradeLink', 'TradeOffers');

        return (new $class())
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param $tradeLink
     * @param array $formData
     * @return mixed
     * @throws InvalidClassException
     */
    public function createTradeOffer($tradeLink, array $formData)
    {
        $class = self::getClass('CreateTradeOffer', 'TradeOffers');

        return (new $class($tradeLink, $formData))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param $tradeOfferId
     * @param array $formData
     * @return mixed
     * @throws InvalidClassException
     */
    public function makeCounteredOffer($tradeOfferId, array $formData)
    {
        $class = self::getClass('CounteredOffer', 'TradeOffers');

        return (new $class($tradeOfferId, $formData))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @return mixed
     * @throws InvalidClassException
     */
    public function acceptTradeOffer($tradeOfferId, array $formData)
    {
        $class = self::getClass('AcceptTradeOffer', 'TradeOffers');

        return (new $class($tradeOfferId, $formData))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @return mixed
     * @throws InvalidClassException
     */
    public function cancelTradeOffer($tradeOfferId)
    {
        $class = self::getClass('CancelTradeOffer', 'TradeOffers');

        return (new $class($tradeOfferId))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @return mixed
     * @throws InvalidClassException
     */
    public function declineTradeOffer($tradeOfferId)
    {
        $class = self::getClass('DeclineTradeOffer', 'TradeOffers');

        return (new $class($tradeOfferId))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param $tradeOfferId
     * @param array $options
     * @return mixed
     * @throws InvalidClassException
     */
    public function getTradeReceipt($tradeOfferId, array $options = [])
    {
        $class = self::getClass('TradeReceipt', 'TradeOffers');

        return (new $class($tradeOfferId, $options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }
}