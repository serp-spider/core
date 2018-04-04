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

    /**
     * @inheritdoc
     */
    public function hasClass($className)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function hasClasses(array $classNames)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function hasAnyClass(array $classNames)
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getAttribute($name)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getTagName()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getNodeValue()
    {
        return $this->domNode->nodeValue;
    }

    /**
     * @inheritdoc
     */
    public function getChildren()
    {
        return new EmptyDomNodeList();
    }

    /**
     * @inheritdoc
     */
    public function getLastChild()
    {
        if (property_exists($this, 'lastChild')) {
            return InternalDocumentWrapper::toDomNodeInterface($this->lastChild);
        } else {
            return new NullDomNode();
        }
    }
}
