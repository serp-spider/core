<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

class EmptyDomNodeList implements DomNodeListInterface
{

    /**
     * @inheritdoc
     */
    public function hasClass($className)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function hasClasses(array $classNames)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function hasAnyClass(array $classNames)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function item($index)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getNodeAt($index)
    {
        return new NullDomNode();
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if ($name === 'length') {
            return $this->count();
        }
    }

    /**
     * @inheritdoc
     */
    public function count()
    {
        return 0;
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        // nothing
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        // nothing
    }
}
