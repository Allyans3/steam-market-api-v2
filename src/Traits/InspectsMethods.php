<?php

namespace SteamApi\Traits;

use SteamApi\Exception\InvalidClassException;
use SteamApi\Services\CS2InspectorService;

trait InspectsMethods
{
    /**
     * @param string $inspectLink
     * @return mixed
     * @throws InvalidClassException
     */
    public function inspectItem(string $inspectLink)
    {
        $class = self::getClass('InspectItem', 'Inspectors');

        return (new $class($inspectLink))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param string $inspectLink
     * @return mixed
     * @throws InvalidClassException
     */
    public function inspectItemV2(string $inspectLink)
    {
        $class = self::getClass('InspectItemV2', 'Inspectors');

        return (new $class($inspectLink))
            ->call($this->proxy, $this->cookies, $this->detailed, $this->curlOpts, $this->multiRequest)
            ->response($this->select, $this->makeHidden);
    }

    /**
     * @param string $inspectLink
     * @return array[]
     * @throws \Exception
     */
    public function inspectLinkDecoder(string $inspectLink)
    {
        return CS2InspectorService::inspect($inspectLink);
    }
}