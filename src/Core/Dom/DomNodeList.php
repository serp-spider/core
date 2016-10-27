<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

use DOMNodeList as BaseDomNodeList;
use Serps\Core\Dom\NodeOverride\QueryableDomElement;

/**
 * @property int $length
 */
class DomNodeList implements \Countable, \Iterator
{

    /**
     * @var BaseDomNodeList
     */
    protected $nodeList;
    protected $documentWrapper;
    protected $itCur = 0;

    public function __construct(BaseDomNodeList $list, DocumentWrapper $doc)
    {
        $this->nodeList = $list;
        $this->documentWrapper = $doc;
    }

    public function item($index)
    {

        $item = $this->nodeList->item($index);
        if ($item instanceof QueryableDomElement) {
        }

        return $this->nodeList->item($index);
    }

    public function __get($name)
    {
        if ($name === 'length') {
            return $this->count();
        }
    }

    public function count()
    {
        return $this->nodeList->length;
    }


    public function current()
    {
        return $this->nodeList->item($this->itCur);
    }

    public function next()
    {
        $this->itCur++;
    }

    public function key()
    {
        return $this->itCur;
    }

    public function valid()
    {
        return $this->itCur < $this->count();
    }

    public function rewind()
    {
        $this->itCur = 0;
    }
}
