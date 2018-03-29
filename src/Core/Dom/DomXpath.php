<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

use DOMXPath as BaseDomXpath;
use DOMNode as BaseDomNode;

class DomXpath extends BaseDomXpath
{

    /**
     * @var DocumentWrapper
     */
    protected $documentWrapper;

    /**
     * DomXpath constructor.
     * @param DocumentWrapper $doc
     */
    public function __construct(InternalDocumentWrapper $doc)
    {
        $this->documentWrapper = $doc;
        parent::__construct($doc->getDom());
    }

    public function query($expr, BaseDomNode $context = null, $registerNodeNS = null)
    {
        $nodeList = parent::query($expr, $context);
        return new DomNodeList($nodeList, $this->documentWrapper);
    }
}
