<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Serp;

use Serps\Core\Serp\ResultDataInterface;
use Traversable;

class ResultSet implements \Countable, \IteratorAggregate
{

    protected $startingAt;

    /**
     * @var ItemPosition[]
     */
    protected $items = [];

    /**
     * ResultSet constructor.
     * @param int $startingAt the position of the first item of the page (starting at 1)
     */
    public function __construct($startingAt)
    {
        $this->startingAt = $startingAt - 1;
    }


    /**
     * @param ResultDataInterface $item
     */
    public function addItem(ResultDataInterface $item)
    {
        $itemCount = count($this->items) + 1;
        $this->items[] = new ItemPosition(
            $itemCount,
            $itemCount + $this->startingAt,
            $item
        );
    }

    /**
     * @return ItemPosition[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param $typeName
     * @return ItemPosition[]
     */
    public function getResultsByType($typeName)
    {
        $results = [];
        foreach ($this->items as $item) {
            if ($item->getType() == $typeName) {
                $results[] = $item;
            }
        }
        return $results;
    }

    /**
     * @param $typeName
     * @return bool
     */
    public function hasType($typeName)
    {
        foreach ($this->items as $item) {
            if ($item->getType() == $typeName) {
                return true;
            }
        }
        return false;
    }

    public function count()
    {
        return count($this->items);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }
}
