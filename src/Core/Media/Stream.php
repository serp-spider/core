<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Media;

use Serps\Core\Media\MediaInterface;

class Stream extends AbstractMedia
{

    protected $stream;

    /**
     * Binary constructor.
     * @param $data
     */
    public function __construct($stream)
    {
        if (!is_resource($stream)) {
            throw new \InvalidArgumentException('$stream parameter should be a stream');
        }
        $this->stream = $stream;
    }

    public function asStream()
    {
        $newStream = fopen('php://memory', 'r+');
        rewind($this->stream);
        stream_copy_to_stream($this->stream, $newStream);
        rewind($newStream);
        return $newStream;
    }

    public function asString()
    {
        rewind($this->stream);
        return stream_get_contents($this->stream);
    }
}
