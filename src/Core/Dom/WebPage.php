<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

use Serps\Core\Url\UrlArchiveInterface;

class WebPage extends DocumentWrapper
{


    /**
     * @var UrlArchiveInterface
     */
    protected $url;

    public function __construct($domString, UrlArchiveInterface $url, $defaultEncoding = null)
    {
        parent::__construct($domString, $defaultEncoding);
        $this->url = $url;
    }

    /**
     * @return UrlArchiveInterface
     */
    public function getUrl()
    {
        return $this->url;
    }

}
