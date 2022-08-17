<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Serp;

class ProxyResult implements ResultDataInterface
{

    /**
     * @var ResultDataInterface
     */
    protected $itemData;

    public function __construct(ResultDataInterface $item)
    {
        $this->itemData = $item;
    }


    /**
     * @param array ...$type
     * @return bool
     */
    public function is($types)
    {
        $types = func_get_args();
        return call_user_func_array([$this->itemData, 'is'], $types);
    }

    public function getDataValue($name)
    {
        return $this->itemData->getDataValue($name);
    }

    public function __get($name)
    {
        return $this->itemData->getDataValue($name);
    }

    public function getData()
    {
        return $this->itemData->getData();
    }


    public function getTypes()
    {
        return $this->itemData->getTypes();
    }

    public function getNodePath() {
        return $this->itemData->getNodePath();
    }

    public function serpFeatureHasPosition() {
        return $this->itemData->serpFeatureHasPosition();
    }

    public function serpFeatureHasSidePosition() {
        return $this->itemData->serpFeatureHasSidePosition();
    }
}
