<?php

namespace SteamApi\Traits;

use SteamApi\Exception\InvalidClassException;

trait MobileConfirmationMethods
{
    /**
     * @param array $options
     * @return mixed
     * @throws InvalidClassException
     */
    public function getMobileConfList(array $options)
    {
        $class = self::getClass('ConfList', 'MobileConfirmations');

        return (new $class($options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param array $options
     * @return mixed
     * @throws InvalidClassException
     */
    public function getMobileConfListV2(array $options)
    {
        $class = self::getClass('ConfListV2', 'MobileConfirmations');

        return (new $class($options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param $confirmationId
     * @param array $options
     * @return mixed
     * @throws InvalidClassException
     */
    public function getMobileConfDetails($confirmationId, array $options)
    {
        $class = self::getClass('ConfDetails', 'MobileConfirmations');

        return (new $class($confirmationId, $options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param array $options
     * @return mixed
     * @throws InvalidClassException
     */
    public function sendMobileConfAjax(array $options)
    {
        $class = self::getClass('ConfAjax', 'MobileConfirmations');

        return (new $class($options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param array $options
     * @return mixed
     * @throws InvalidClassException
     */
    public function sendMobileMultiConfAjax(array $options)
    {
        $class = self::getClass('MultiConfAjax', 'MobileConfirmations');

        return (new $class($options))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }
}