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
    public function getTypes();

    /**
     * Check if the element has one of the given type
     * @param array ...$type
     * @return mixed
     */
    public function is($types);

    public function getDataValue($name);

    public function getData();

    public function getNodePath();

    public function serpFeatureHasPosition();

    public function serpFeatureHasSidePosition();

    /**
     * Shortcut for getDataValue()
     */
    public function __get($name);
}
