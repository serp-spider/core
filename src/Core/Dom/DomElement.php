<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

class DomElement extends \DOMElement implements DomNodeInterface
{

    private function getClassList()
    {
        $classList = $this->getAttribute('class');

        if ($classList) {
            return explode(' ', $classList);
        }

        return [];
    }

    public function hasClass($className)
    {
        return in_array($className, $this->getClassList());
    }

    public function hasAnyClass(array $classNames)
    {
        $classList = $this->getClassList();

        foreach ($classNames as $className) {
            if (in_array($className, $classList)) {
                return true;
            }
        }

        return false;
    }

    public function hasClasses(array $classNames)
    {
        $classList = $this->getClassList();

        if (!empty($classList)) {
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
