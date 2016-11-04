<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Http;

use Psr\Http\Message\RequestInterface;
use Serps\Core\Cookie\CookieJarInterface;
use Serps\Core\UrlArchive;

/**
 * This class is aimed for test only
 *
 * It a http client implementation that stacks requests and returns an empty response
 */
class StackingHttpClient implements \Countable, HttpClientInterface
{

    protected $requestStack = [];

    public function resetStack()
    {
        $this->requestStack = [];
    }

    public function count()
    {
        return count($this->requestStack);
    }

    /**
     * @return []
     */
    public function getStack()
    {
        return $this->requestStack;
    }

    public function sendRequest(
        RequestInterface $request,
        ProxyInterface $proxy = null,
        CookieJarInterface $cookieJar = null
    ) {

        $requestData = new \stdClass();

        $requestData->request = $request;
        $requestData->cookieJar = $cookieJar;
        $requestData->proxy = $proxy;

        $this->requestStack[] = $requestData;

        return new SearchEngineResponse(
            [],
            200,
            '',
            false,
            UrlArchive::fromString($request->getUri()),
            UrlArchive::fromString($request->getUri()),
            $proxy
        );
    }
}
