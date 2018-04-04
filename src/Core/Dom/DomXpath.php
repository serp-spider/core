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

    /**
     * @inheritdoc
     */
    public function query($expr, BaseDomNode $context = null, $registerNodeNS = null)
    {

        // NullDomNode is an addition to SERPS and must be handled differently
        if ($context instanceof NullDomNode) {
            if ($expr{0} == '/') {
                $context = null;
            } else {
                return new EmptyDomNodeList();
            }
        }

        $nodeList = parent::query($expr, $context);
        return new DomNodeList($nodeList, $this->documentWrapper);
    }
}
