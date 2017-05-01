<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Browser;

use Psr\Http\Message\RequestInterface;
use Serps\Core\Cookie\Cookie;
use Serps\Core\Cookie\CookieJarInterface;
use Serps\Core\Http\HttpClientInterface;
use Serps\Core\Http\ProxyInterface;
use Serps\Core\Psr7\RequestBuilder;
use Serps\Core\Url\UrlArchiveInterface;

class Browser extends AbstractBrowser
{
    protected $acceptLanguage;
    protected $userAgent;

    /**
     * @var null|CookieJarInterface
     */
    protected $cookieJar;

    /**
     * @var null|ProxyInterface
     */
    protected $proxy;

    /**
     * @var HttpClientInterface
     */
    protected $httpClient;

    /**
     * @param string|null $userAgent the user agent string
     * @param string|null $acceptLanguage the accept language header. Default to 'en-US,en;q=0.8'
     * @param CookieJarInterface|null $cookieJar a cookie jar to use for requests
     * @param ProxyInterface|null $proxy a proxy to use for requests
     */
    public function __construct(
        HttpClientInterface $httpClient,
        $userAgent = null,
        $acceptLanguage = null,
        CookieJarInterface $cookieJar = null,
        ProxyInterface $proxy = null
    ) {

        $this->httpClient = $httpClient;
        $this->userAgent = $userAgent ? $userAgent : 'serps';
        $this->acceptLanguage = $acceptLanguage ? $acceptLanguage : 'en-US,en;q=0.8';
        $this->cookieJar = $cookieJar;
        $this->proxy = $proxy;
    }

    /**
     * @return null|string
     */
    public function getAcceptLanguage()
    {
        return $this->acceptLanguage;
    }

    /**
     * @return null|string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @return null|CookieJarInterface
     */
    public function getCookieJar()
    {
        return $this->cookieJar;
    }

    /**
     * @return null|ProxyInterface
     */
    public function getProxy()
    {
        return $this->proxy;
    }

    /**
     * @return HttpClientInterface
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param null|string $acceptLanguage
     */
    public function setAcceptLanguage($acceptLanguage)
    {
        $this->acceptLanguage = $acceptLanguage;
    }

    /**
     * @param null|string $userAgent
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @param null|CookieJarInterface $cookieJar
     */
    public function setCookieJar(CookieJarInterface $cookieJar = null)
    {
        $this->cookieJar = $cookieJar;
    }

    /**
     * @param null|ProxyInterface $proxy
     */
    public function setProxy(ProxyInterface $proxy = null)
    {
        $this->proxy = $proxy;
    }
}
