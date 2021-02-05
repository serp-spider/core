<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

interface DomNodeInterface
{

    public function hasClass($className);
    public function hasClasses(array $classNames);
    public function hasAnyClass(array $classNames);
    public function getAttribute(string $name);
    public function getTagName();
    public function getNodeValue();

    /**
     * @return DomNodeList
     */
    public function getChildren();

    /**
     * @return DomNodeInterface
     */
    public function getLastChild();
}
