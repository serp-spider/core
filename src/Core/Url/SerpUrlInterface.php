<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Url;

use Psr\Http\Message\RequestInterface;

interface SerpUrlInterface
{

    public function getUrl();

    /**
     * @return RequestInterface
     */
    public function buildRequest();

    public function setSearchTerm($search);
    public function getSearchTerm();
}
