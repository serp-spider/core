<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

class DocumentWrapper
{

    protected $xpath;

    /**
     * @var \DOMDocument
     */
    protected $dom;



    /**
     * @param string $domString the dom document as a string
     * @param string $defaultEncoding encoding of the document.
     * Only useful if the document does not define xml properties at beginning of the document. Default to 'UTF-8'
     */
    public function __construct($domString, $defaultEncoding = null)
    {
        // if xml tag is already specified we leave it as it is
        // but if it's note the case we will use the given encoding
        if (substr($domString, 0, 5) !== '<?xml') {
            if (!$defaultEncoding) {
                $defaultEncoding = 'UTF-8';
            }

            $domString = '<?xml encoding="' . $defaultEncoding . '">' . $domString;
        }

        // Load DOM
        $this->dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $this->dom->loadHTML($domString);
        libxml_use_internal_errors(false);
        libxml_clear_errors();
    }

    /**
     * get the object xpath to query it
     * @return \DOMXPath
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
