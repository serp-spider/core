<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

use DOMNodeList as BaseDomNodeList;

/**
 * @property int $length
 */
class DomNodeList implements DomNodeListInterface
{

    /**
     * @var BaseDomNodeList
     */
    protected $nodeList;
    protected $documentWrapper;
    protected $itCur = 0;

    /**
     * DomNodeList constructor.
     * @param BaseDomNodeList $list
     * @param DocumentWrapper $doc
     */
    public function __construct(BaseDomNodeList $list, InternalDocumentWrapper $doc)
    {
        $this->nodeList = $list;
        $this->documentWrapper = $doc;
    }

    /**
     * @inheritdoc
     */
    public function hasClass($className)
    {
        for ($i = 0; $i < $this->nodeList->length; $i++) {
            if ($this->getNodeAt($i)->hasClass($className)) {
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
        for ($i = 0; $i < $this->nodeList->length; $i++) {
            if ($this->getNodeAt($i)->hasClasses($classNames)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function hasAnyClass(array $classNames)
    {
        for ($i = 0; $i < $this->nodeList->length; $i++) {
            if ($this->getNodeAt($i)->hasAnyClass($classNames)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function item($index)
    {
        $item = $this->nodeList->item($index);

        if (!$item) {
            return null;
        }

        if (!$item instanceof DomNodeInterface) {
            return new OtherDomNode($item);
        }

        return $item;
    }

    /**
     * @inheritdoc
     */
    public function getNodeAt($index)
    {
        $item = $this->nodeList->item($index);

        return InternalDocumentWrapper::toDomNodeInterface($item);
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
        return $this->nodeList->length;
    }

    /**
     * @inheritdoc
     */
    public function current()
    {
        return $this->nodeList->item($this->itCur);
    }

    /**
     * @inheritdoc
     */
    public function next()
    {
        $this->itCur++;
    }

    /**
     * @inheritdoc
     */
    public function key()
    {
        return $this->itCur;
    }

    /**
     * @inheritdoc
     */
    public function valid()
    {
        return $this->itCur < $this->count();
    }

    /**
     * @inheritdoc
     */
    public function rewind()
    {
        $this->itCur = 0;
    }
}
