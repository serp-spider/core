<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

interface DomNodeListInterface extends \Countable, \Iterator
{

    /**
     * Check if at least one of the elements in the collection as the given class
     * @param $className
     * @return bool
     */
    public function hasClass($className);

    /**
     * Check if at least one of the elements in the collection as all the given classes
     * @param $className
     * @return bool
     */
    public function hasClasses(array $classNames);

    /**
     * Check if at least one of the elements in the collection as one of the given classes
     * @param $className
     * @return bool
     */
    public function hasAnyClass(array $classNames);

    /**
     * @param $index
     * @return DomNodeInterface|null
     */
    public function item($index);

    /**
     * This is almost the same as item() method but while item() might return null if an element does not exist this
     * method will always return a valid object
     * @param $index
     * @return DomNodeInterface
     */
    public function getNodeAt($index);
}
