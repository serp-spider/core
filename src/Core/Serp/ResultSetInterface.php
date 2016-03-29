<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Serp;

interface ResultSetInterface extends \Countable, \IteratorAggregate, \ArrayAccess
{

    /**
     * @return ResultDataInterface[]
     */
    public function getItems();

    /**
     * @param $types
     * @return ResultSetInterface
     */
    public function getResultsByType($types);

    /**
     * @param $types
     * @return bool
     */
    public function hasType($types);
}
