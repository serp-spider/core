<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Serp;

class ResultSet implements ResultSetInterface
{

    /**
     * @var ItemPosition[]
     */
    protected $items = [];

    public function addItems(array $items)
    {
        $this->items = array_merge($this->items, $items);
    }

    /**
     * @param ResultDataInterface $item
     */
    public function addItem(ResultDataInterface $item)
    {
        $this->items[] = $item;
    }

    /**
     * @return ResultDataInterface[]
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
        $items = new ResultSet();
        foreach ($this->items as $item) {
            if (call_user_func_array([$item, 'is'], $types)) {
                $items->addItem($item);
            }
        }
        return $items;
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

    public function offsetExists($offset)
    {
        return key_exists($offset, $this->items);
    }

    public function offsetGet($offset)
    {
        return $this->items[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }
}
