<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface HttpClientInterface
{
    /**
     * Sends a request with an optional given proxy and returns the http response
     *
     * @param RequestInterface $request
     * @param ProxyInterface $proxy
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request, ProxyInterface $proxy = null);
}
