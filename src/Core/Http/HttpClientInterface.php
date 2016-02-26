<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Http;

use Psr\Http\Message\RequestInterface;
use Serps\Core\Cookie\CookieJarInterface;
use Serps\Core\Http\SearchEngineResponse;

interface HttpClientInterface
{
    /**
     * Sends a request with an optional given proxy and returns the http response
     *
     * @param RequestInterface $request
     * @param ProxyInterface $proxy
     * @param CookieJarInterface|null $cookieJar
     * @return SearchEngineResponse
     */
    public function sendRequest(
        RequestInterface $request,
        ProxyInterface $proxy = null,
        CookieJarInterface $cookieJar = null
    );
}
