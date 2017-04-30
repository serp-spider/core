<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

class DomElement extends \DOMElement implements DomNodeInterface
{

    public function hasClass($className)
    {
        $classList = $this->getAttribute('class');

        if ($classList) {
            $classList = explode(' ', $classList);
            return in_array($className, $classList);
        }

        return false;
    }

    public function getTagName()
    {
        return $this->tagName;
    }

    public function getNodeValue()
    {
        return $this->nodeValue;
    }
}
