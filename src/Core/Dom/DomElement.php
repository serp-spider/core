<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

class DomElement extends \DOMElement implements DomNodeInterface
{

    /**
     * @inheritdoc
     */
    private function getClassList()
    {
        $classList = $this->getAttribute('class');

        if ($classList) {
            return explode(' ', $classList);
        }

        return [];
    }

    /**
     * @inheritdoc
     */
    public function hasClass($className)
    {
        return in_array($className, $this->getClassList());
    }

    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
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

    /**
     * @inheritdoc
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * @inheritdoc
     */
    public function getNodeValue()
    {
        return $this->nodeValue;
    }

    /**
     * @inheritdoc
     */
    public function getChildren()
    {
        return new DomNodeList($this->childNodes, new InternalDocumentWrapper($this->ownerDocument));
    }
}
