<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Serp;

class ItemPosition implements ResultDataInterface
{

    protected $position;

    /**
     * @var ResultDataInterface
     */
    protected $itemData;

    /**
     * ItemPosition constructor.
     * @param $position
     * @param ResultDataInterface $itemData
     */
    public function __construct($position, ResultDataInterface $itemData)
    {
        $this->position = $position;
        $this->itemData = $itemData;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function getType()
    {
        return $this->itemData->getType();
    }

    public function is($type)
    {
        return $this->itemData->is($type);
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
