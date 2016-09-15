<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Media;

abstract class AbstractMedia implements MediaInterface
{

    public function __toString()
    {
        return $this->asString();
    }

    public function asStream()
    {
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $this->asString());
        rewind($stream);
        return $stream;
    }

    public function asBase64()
    {
        return base64_encode($this->asString());
    }

    public function saveFile($fileName)
    {
        file_put_contents($fileName, $this->asString());
    }
}
