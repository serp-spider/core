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

    public function hasClasses(array $classNames)
    {
        $classList = $this->getAttribute('class');

        if ($classList) {
            $classList = explode(' ', $classList);
            foreach ($classNames as $className) {
                if (!in_array($className, $classList)) {
                    return false;
                }
            }

            return true;
        }

        return empty($classNames);
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
