<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

class DocumentWrapper extends InternalDocumentWrapper
{


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
        $dom = new \DOMDocument();
        $previousUseIE = libxml_use_internal_errors(true);
        $dom->loadHTML($domString);
        libxml_use_internal_errors($previousUseIE);
        libxml_clear_errors();

        $dom->registerNodeClass(\DOMElement::class, DomElement::class);
        parent::__construct($dom);
    }
}
