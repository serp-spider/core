<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Serp;

class CompositeResultSet implements ResultSetInterface
{

    /**
     * @var ResultSetInterface[]
     */
    protected $resultSets;

    /**
     * CompositeResultSet constructor.
     * @param ResultSetInterface[] $resultSets
     */
    public function __construct(array $resultSets = [])
    {
        $this->resultSets = $resultSets;
    }

    /**
     * @return ResultSetInterface[]
     */
    public function getResultSets()
    {
        return $this->resultSets;
    }

    /**
     * Adds a result set to the internal list
     * @param ResultSetInterface $resultSet
     */
    public function addResultSet(ResultSetInterface $resultSet)
    {
        $this->resultSets[] = $resultSet;
    }

    /**
     * @return ResultDataInterface[]
     */
    public function getItems()
    {

        $items = [];

        foreach ($this->resultSets as $resultSet) {
            $items = array_merge($items, $resultSet->getItems());
        }

        return $items;
    }

    /**
     * Get all the results matching one of the given types
     * @param array ...$types
     * @return ResultDataInterface[]
     */
    public function getResultsByType($types)
    {
        $types = func_get_args();
        $items = new CompositeResultSet();
        foreach ($this->resultSets as $resultSet) {
            $items->addResultSet(
                call_user_func_array([$resultSet, 'getResultsByType'], $types)
            );
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
        foreach ($this->resultSets as $resultSet) {
            if (call_user_func_array([$resultSet, 'hasType'], $types)) {
                return true;
            }
        }
        return false;
    }

    public function count()
    {
        $count = 0;
        foreach ($this->resultSets as $resultSet) {
            $count += count($resultSet);
        }

        return $count;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->getItems());
    }



    public function offsetExists($offset)
    {
        return key_exists($offset, $this->getItems());
    }

    public function offsetGet($offset)
    {
        return $this->getItems()[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new \Exception('Cannot set items in resultset, please use addResultSet()');
    }

    public function offsetUnset($offset)
    {
        throw new \Exception('Deleting item is forbidden');
    }
}
