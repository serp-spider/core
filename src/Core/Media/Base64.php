<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Media;

use Serps\Core\Media\MediaInterface;

class Base64 extends AbstractMedia
{

    protected $data;

    /**
     * Binary constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function asBase64()
    {
        return $this->data;
    }

    public function asString()
    {
        return base64_decode($this->data);
    }
}
