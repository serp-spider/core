<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

/**
 * @internal
 */
class InternalDocumentWrapper
{

    /**
     * @var \DOMDocument
     */
    protected $dom;

    private $xpath;

    /**
     * Transforms the given dom node to a domNodeInterface instance
     * @param \DOMNode|null $item
     * @return DomNodeInterface
     */
    public static function toDomNodeInterface(\DOMNode $item = null)
    {
        if (!$item) {
            return new NullDomNode();
        }

        if (!$item instanceof DomNodeInterface) {
            return new OtherDomNode($item);
        }

        return $item;
    }

    /**
     * InternalDocumentWrapper constructor.
     * @param \DOMDocument $dom
     */
    public function __construct(\DOMDocument $dom)
    {
        $this->dom = $dom;
    }

    /**
     * get the object xpath to query it
     * @return DomXpath
     */
    public function getXpath()
    {
        if (null === $this->xpath) {
            $this->xpath = new DomXpath($this);
        }
        return $this->xpath;
    }

    /**
     * @return \DOMDocument
     */
    public function getDom()
    {
        return $this->dom;
    }

    /**
     * Runs a xpath query against the wrapped dom object
     *
     * That's a shortcut for  \DOMXPath::query()
     *
     * @link http://php.net/manual/en/domxpath.query.php
     *
     * @param string $query the xpath query
     * @param \DOMNode|null $node the context node for the query, leave it null to query the root
     * @return DomNodeList
     */
    public function xpathQuery($query, $node = null)
    {
        return $this->getXpath()->query($query, $node);
    }

    /**
     * Runs a css query against the wrapped dom object. Internally the css will translate to xpath
     *
     * @link http://php.net/manual/en/domxpath.query.php
     *
     * @param string $query the css query
     * @param \DOMNode|null $node the context node for the query, leave it null to query the root
     * @return DomNodeList
     */
    public function cssQuery($query, $node = null)
    {
        return $this->getXpath()->query(Css::toXPath($query), $node);
    }
}
