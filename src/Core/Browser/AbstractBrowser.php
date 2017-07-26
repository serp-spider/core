<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Browser;

use Psr\Http\Message\RequestInterface;
use Serps\Core\Psr7\RequestBuilder;
use Serps\Core\Url\UrlArchiveInterface;
use function strtoupper;
use function var_dump;

abstract class AbstractBrowser implements BrowserInterface
{

    protected $defaultHeaders = [];
    protected $defaultHeadersUC = [];

    /**
     * @inheritdoc
     */
    public function sendRequest(RequestInterface $request)
    {
        $request = $this->prepareRequest($request);
        return $this->getHttpClient()->sendRequest($request, $this->getProxy(), $this->getCookieJar());
    }

    /**
     * @return null|string
     */
    public function getAcceptLanguage()
    {
        return $this->getDefaultHeaderValue('ACCEPT-LANGUAGE');
    }

    /**
     * @return null|string
     */
    public function getUserAgent()
    {
        return $this->getDefaultHeaderValue('USER-AGENT');
    }

    /**
     * Adds a default header to be sent with every request
     * @param $headerName
     * @param $headerValue
     */
    public function setDefaultHeader($headerName, $headerValue)
    {
        $this->defaultHeaders[$headerName] = $headerValue;
        $this->defaultHeadersUC[strtoupper($headerName)] = $headerName;
    }

    /**
     * Check if the header name is defined as a default header
     * @param $headerName
     * @return bool
     */
    public function hasDefaultHeader($headerName)
    {
        return isset($this->defaultHeadersUC[strtoupper($headerName)]);
    }

    /**
     * Get the value of the default given default header name or null if not set
     * @param $headerName
     * @return bool
     */
    public function getDefaultHeaderValue($headerName)
    {
        $headerName = strtoupper($headerName);
        if (isset($this->defaultHeadersUC[$headerName])) {
            return $this->defaultHeaders[$this->defaultHeadersUC[$headerName]];
        } else {
            return null;
        }
    }

    /**
     * @inheritdoc
     */
    public function prepareRequest(RequestInterface $request)
    {
        $headers = $this->getDefaultHeaders();
        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }
        return $request;
    }

    /**
     * @inheritdoc
     */
    public function getDefaultHeaders()
    {
        return $this->defaultHeaders;
    }

    /**
     * @inheritdoc
     */
    public function requestFromUrl(UrlArchiveInterface $url)
    {
        $headers = $this->getDefaultHeaders();

        $request = RequestBuilder::buildRequest(
            (string) $url,
            'GET',
            $headers,
            'php://memory'
        );

        return $request;
    }

    /**
     * @inheritdoc
     */
    public function navigateToUrl(UrlArchiveInterface $url)
    {
        $request = $this->requestFromUrl($url);
        return $this->sendRequest($request);
    }
}
