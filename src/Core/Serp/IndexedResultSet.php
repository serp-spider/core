<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Serp;

use Serps\Core\Serp\ResultDataInterface;
use Traversable;

class IndexedResultSet implements ResultSetInterface
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
     * Get all the results matching one of the given types
     * @param array ...$types
     * @return ItemPosition[]
     */
    public function getResultsByType($types)
    {
        $types = func_get_args();
        $results = [];
        foreach ($this->items as $item) {
            if (call_user_func_array([$item, 'is'], $types)) {
                $results[] = $item;
            }
        }
        return $results;
    }

    /**
     * Checks if the ResultSet has one of the given types
     * @param string ...$types
     * @return bool
     */
    public function hasType($types)
    {
        $types = func_get_args();

        foreach ($this->items as $item) {
            if (call_user_func_array([$item, 'is'], $types)) {
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
