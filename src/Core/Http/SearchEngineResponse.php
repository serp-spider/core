<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Http;

use Serps\Core\Cookie\Cookie;
use Serps\Core\UrlArchive;

class SearchEngineResponse
{

    protected $httpResponseHeaders;
    protected $headerNames;
    protected $httpResponseStatus;

    protected $pageEvaluated;
    protected $pageContent;

    protected $initialUrl;
    protected $effectiveUrl;
    protected $proxy;


    /**
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
        ProxyInterface $proxy = null
    ) {
        foreach ($httpResponseHeaders as $k => $v) {
            $this->headerNames[strtoupper($k)] = $k;
        }
        $this->httpResponseHeaders = $httpResponseHeaders;

        $this->httpResponseStatus = $httpResponseStatus;
        $this->pageEvaluated = (bool)$pageEvaluated;
        $this->pageContent = $pageContent;
        $this->initialUrl = $initialUrl;
        $this->effectiveUrl = $effectiveUrl;
        $this->proxy = $proxy;
    }

    /**
     * Get the header value or null if it does not exist
     * @param $headerName
     * @return null
     */
    public function getHeader($headerName)
    {
        if ($this->hasHeader($headerName)) {
            return $this->httpResponseHeaders[$this->headerNames[strtoupper($headerName)]];
        }
        return null;
    }

    /**
     * Check if the given header was in the http response
     * @param $headerName
     * @return bool
     */
    public function hasHeader($headerName)
    {
        return isset($this->headerNames[strtoupper($headerName)]);
    }

    /**
     * all http response headers
     * @return array
     */
    public function getHeaders()
    {
        return $this->httpResponseHeaders;
    }

    /**
     * the http response status code
     * @return int
     */
    public function getHttpResponseStatus()
    {
        return $this->httpResponseStatus;
    }

    /**
     * Will return true if the page/javascript were evaluated, in this case dom might be updated
     * @return bool
     */
    public function isPageEvaluated()
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
     * the url that initiated the request
     * @return UrlArchive
     */
    public function getInitialUrl()
    {
        return $this->initialUrl;
    }

    /**
     * the final url of the request. In case of a redirection, that will be the final url of the redirection
     * @return UrlArchive
     */
    public function getEffectiveUrl()
    {
        return $this->effectiveUrl;
    }

    /**
     * The proxy that was used. Will be null if no proxy was used
     * @return ProxyInterface|null
     */
    public function getProxy()
    {
        return $this->proxy;
    }
}
