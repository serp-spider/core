<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Browser;

use Psr\Http\Message\RequestInterface;
use Serps\Core\Psr7\RequestBuilder;
use Serps\Core\Url\UrlArchiveInterface;

abstract class AbstractBrowser implements BrowserInterface
{

    /**
     * @inheritdoc
     */
    public function sendRequest(RequestInterface $request)
    {
        $request = $this->prepareRequest($request);
        return $this->getHttpClient()->sendRequest($request, $this->getProxy(), $this->getCookieJar());
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
        return [
            'User-Agent'      => $this->getUserAgent(),
            'Accept-Language' => $this->getAcceptLanguage()
        ];
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
