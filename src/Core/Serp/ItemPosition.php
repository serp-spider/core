<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Serp;

class ItemPosition implements ResultDataInterface
{

    protected $positionOnPage;
    protected $realPosition;

    /**
     * @var ResultDataInterface
     */
    protected $itemData;

    /**
     * ItemPosition constructor.
     * @param $position
     * @param ResultDataInterface $itemData
     */
    public function __construct($positionOnPage, $realPosition, ResultDataInterface $itemData)
    {
        $this->positionOnPage = $positionOnPage;
        $this->realPosition = $realPosition;
        $this->itemData = $itemData;
    }

    /**
     * @return int the position of the item on the page (starting at 1)
     */
    public function getOnPagePosition()
    {
        return $this->positionOnPage;
    }

    /**
     * @return int the general position of the item (starting at 1)
     */
    public function getRealPosition()
    {
        return $this->realPosition;
    }

    public function getType()
    {
        return $this->itemData->getType();
    }

    public function is(...$type)
    {
        return $this->itemData->is(...$type);
    }

    public function getDataValue($name)
    {
        return $this->itemData->getDataValue($name);
    }

    public function getData()
    {
        return $this->itemData->getData();
    }
}
