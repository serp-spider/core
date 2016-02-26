<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Http;

use Psr\Http\Message\RequestInterface;
use Serps\Core\Http\SearchEngineResponse;

interface HttpClientInterface
{
    /**
     * Sends a request with an optional given proxy and returns the http response
     *
     * @param RequestInterface $request
     * @param ProxyInterface $proxy
     * @return SearchEngineResponse
     */
    public function sendRequest(RequestInterface $request, ProxyInterface $proxy = null);
}
