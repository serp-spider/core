<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Url;

use Psr\Http\Message\RequestInterface;

interface SerpUrlInterface
{

    public function buildUrl();

    /**
     * @return RequestInterface
     */
    public function buildRequest();

    public function getSearchTerm();
}
