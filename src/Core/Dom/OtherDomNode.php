<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

class OtherDomNode implements DomNodeInterface
{
    /**
     * @var \DOMNode
     */
    protected $domNode;

    /**
     * OtherDomNode constructor.
     * @param \DOMNode $domNode
     */
    public function __construct(\DOMNode $domNode)
    {
        $this->domNode = $domNode;
    }


    public function hasClass($className)
    {
        return false;
    }

    public function getAttribute($name)
    {
        return null;
    }

    public function getTagName()
    {
        return null;
    }

    public function getNodeValue()
    {
        return $this->domNode->nodeValue;
    }
}
