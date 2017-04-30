<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

interface DomNodeInterface
{

    public function hasClass($className);
    public function getAttribute($name);
    public function getTagName();
    public function getNodeValue();
}
