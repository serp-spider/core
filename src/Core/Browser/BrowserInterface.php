<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Browser;

use Psr\Http\Message\RequestInterface;
use Serps\Core\Http\HttpClientInterface;
use Serps\Core\Http\SearchEngineResponse;
use Serps\Core\Url\UrlArchiveInterface;

/**
 * A browser instance centralizes information for requests: user agent, accept language header, cookies and  proxy
 *
 * the class Serps\Core\Browser\AbstractBrowser helps to implement this interface
 *
 * @see Serps\Core\Browser\AbstractBrowser
 */
interface BrowserInterface
{

    public function getUserAgent();

    public function getAcceptLanguage();

    public function getDefaultHeaders();

    public function getProxy();

    public function getCookieJar();

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient();

    /**
     * Transform the given request to match the browser configuration (user agent, accept language...) and sends
     * it with the browser instance configuration (http client, cookie, proxy)
     * @param RequestInterface $request
     * @return SearchEngineResponse
     */
    public function sendRequest(RequestInterface $request);

    /**
     * Prepare a request from the given request. the returned request will have some modified headers to
     * match the browser instance configuration (user agent, accept language...)
     * @param RequestInterface $request
     * @return RequestInterface
     */
    public function prepareRequest(RequestInterface $request);


    /**
     * Prepare a request for the given url. The request will respect the configuration of the browser instance
     * @param UrlArchiveInterface $url
     * @return RequestInterface
     */
    public function requestFromUrl(UrlArchiveInterface $url);

    /**
     * Prepare a request for the given url and sends it
     *
     * Equivalent to requestFromUrl + sendRequest
     *
     * @param UrlArchiveInterface $url
     * @return SearchEngineResponse
     */
    public function navigateToUrl(UrlArchiveInterface $url);
}
