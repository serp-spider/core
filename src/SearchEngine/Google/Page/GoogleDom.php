<?php
/**
 * @license see LICENSE
 */

namespace Serps\SearchEngine\Google\Page;

use Psr\Http\Message\ResponseInterface;
use Serps\SearchEngine\Google\GoogleUrl;
use Serps\SearchEngine\Google\GoogleUrlArchive;

class GoogleDom
{

    protected $xpath;

    /**
     * @var \DOMDocument
     */
    protected $dom;

    /**
     * @var GoogleUrlArchive
     */
    protected $url;

    public function __construct($domString, GoogleUrlArchive $url)
    {
        $this->url = $url;

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
            $this->xpath=new \DOMXPath($this->dom);
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
     * @return GoogleUrlArchive
     */
    public function getUrl()
    {
        return $this->url;
    }
}
