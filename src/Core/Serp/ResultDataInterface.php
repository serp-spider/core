<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Serp;

interface ResultDataInterface
{


    /**
     * Get the type of the element
     * @return mixed
     */
    public function getType();

    /**
     * Check if the element is of the given type
     * @param $type
     * @return mixed
     */
    public function is($type);

    public function getDataValue($name);

    public function getData();
}
