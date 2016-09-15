<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Media;

interface MediaInterface
{

    /**
     * @return resource
     */
    public function asStream();

    /**
     * @return string
     */
    public function asBase64();

    /**
     * @return string
     */
    public function asString();

    /**
     * @param $fileName
     * @return boolean
     */
    public function saveFile($fileName);

    public function __toString();
}
