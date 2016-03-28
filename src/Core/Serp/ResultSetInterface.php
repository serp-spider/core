<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Serp;

interface ResultSetInterface extends \Countable, \IteratorAggregate
{

    public function getItems();
    public function getResultsByType($types);
    public function hasType($types);
}
