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
     * The implementation MUST set the following headers on the response:
     *
     *  - ``X-SERPS-EFFECTIVE-URL``: in case of a redirection that will be the final url redirected to
     *  - ``X-SERPS-PROXY``: the proxy used for the request in the form ``user:password@ip:port``
     *
     * @param RequestInterface $request
     * @param ProxyInterface $proxy
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request, ProxyInterface $proxy = null);
}
