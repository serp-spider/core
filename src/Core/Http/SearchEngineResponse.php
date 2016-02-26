<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Http;

use Serps\Core\UrlArchive;

class SearchEngineResponse
{

    protected $httpResponseHeaders;
    protected $httpResponseStatus;

    protected $pageEvaluated;
    protected $pageContent;

    protected $initialUrl;
    protected $effectiveUrl;
    protected $proxy;

    protected $cookies;

    /**
     * SearchEngineResponse constructor.
     * @param $httpResponseHeaders [] the http headers form the response
     * @param $httpResponseStatus int the http status code from the respsone
     * @param $pageContent string content of the page evaluated or not
     * @param $pageEvaluated bool page was evaluated meaning the $pageContent might be changed by javascript
     * @param $initialUrl UrlArchive the initial url
     * @param $effectiveUrl UrlArchive the effective url that is the last url after a redirection
     * @param $proxy ProxyInterface|null the proxy used for the query
     */
    public function __construct(
        array $httpResponseHeaders,
        $httpResponseStatus,
        $pageContent,
        $pageEvaluated,
        UrlArchive $initialUrl,
        UrlArchive $effectiveUrl,
        array $cookies,
        ProxyInterface $proxy = null
    ) {
        foreach ($httpResponseHeaders as $k => $v) {
            $this->httpResponseHeaders[strtoupper($k)] = $v;
        }
        $this->httpResponseStatus = $httpResponseStatus;
        $this->pageEvaluated = $pageEvaluated;
        $this->pageContent = $pageContent;
        $this->initialUrl = $initialUrl;
        $this->effectiveUrl = $effectiveUrl;
        $this->proxy = $proxy;
        $this->cookies = $cookies;
    }

    /**
     * Get the header value or null if it does not exist
     * @param $headerName
     * @return null
     */
    public function getHeader($headerName)
    {
        if (isset($this->httpResponseHeaders[$headerName])) {
            return $this->httpResponseHeaders[$headerName];
        }
        return null;
    }

    /**
     * @return array
     */
    public function getHttpResponseHeaders()
    {
        return $this->httpResponseHeaders;
    }

    /**
     * @return int
     */
    public function getHttpResponseStatus()
    {
        return $this->httpResponseStatus;
    }

    /**
     * @return bool
     */
    public function getPageEvaluated()
    {
        return $this->pageEvaluated;
    }

    /**
     * @return string
     */
    public function getPageContent()
    {
        return $this->pageContent;
    }

    /**
     * @return UrlArchive
     */
    public function getInitialUrl()
    {
        return $this->initialUrl;
    }

    /**
     * @return UrlArchive
     */
    public function getEffectiveUrl()
    {
        return $this->effectiveUrl;
    }

    /**
     * @return ProxyInterface
     */
    public function getProxy()
    {
        return $this->proxy;
    }
}
