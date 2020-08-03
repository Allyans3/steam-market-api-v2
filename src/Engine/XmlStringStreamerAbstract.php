<?php

namespace SteamApi\Engine;

use Prewk\XmlStringStreamer;
use Prewk\XmlStringStreamer\Parser;
use Prewk\XmlStringStreamer\Stream;

abstract class XmlStringStreamerAbstract
{
    private $file;

    public function loadFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function loadString($string)
    {
        $this->file = fopen('php://memory','r+');
        fwrite($this->file, $string);
        rewind($this->file);

        return $this;
    }

    public function getStreamer($options = [])
    {
        return new XmlStringStreamer($this->getParser($options), $this->getStream());
    }

    private function getStream()
    {
        return new Stream\File($this->file, 1024);
    }

    private function getParser($options)
    {
        return new Parser\StringWalker($options);
    }
}
