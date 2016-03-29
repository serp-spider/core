<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Serp;

use Serps\Core\Serp\ResultDataInterface;
use Traversable;

/**
 * @method ItemPosition[] getItems()
 */
class IndexedResultSet extends ResultSet
{

    protected $startingAt;

    /**
     * ResultSet constructor.
     * @param int $startingAt the position of the first item of the page (starting at 1)
     */
    public function __construct($startingAt = 1)
    {
        $this->startingAt = $startingAt - 1;
    }

    /**
     * @param ResultDataInterface $item
     */
    public function addItem(ResultDataInterface $item)
    {
        $itemCount = count($this->items) + 1;
        parent::addItem(new ItemPosition(
            $itemCount,
            $itemCount + $this->startingAt,
            $item
        ));
    }
}
