<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Browser;

use Serps\Core\Cookie\ArrayCookieJar;
use Serps\Core\Http\Proxy;
use Serps\Core\Http\StackingHttpClient;
use Serps\Core\Browser\Browser;
use Serps\Core\Psr7\RequestBuilder;
use Zend\Diactoros\Request;

/**
 * @covers Serps\Core\Http\StackingHttpClient
 * @covers Serps\Core\Browser\Browser
 * @covers Serps\Core\Browser\AbstractBrowser
 */
class BrowserTest extends \PHPUnit_Framework_TestCase
{

    public function testSendRequest()
    {
        $httpClient = new StackingHttpClient();

        $browser = new Browser(
            $httpClient
        );

        ////
        // test default request
        $request = RequestBuilder::buildRequest('http://foo.com');

        $browser->sendRequest($request);

        $this->assertCount(1, $httpClient);
        $this->assertEquals('http://foo.com', (string) $httpClient->getStack()[0]->request->getUri());
        $this->assertEquals(
            [
                'User-Agent' => ['serps'],
                'Accept-Language' => ['en-US,en;q=0.8'],
                'Host' => ['foo.com']
            ],
            $httpClient->getStack()[0]->request->getHeaders()
        );
        $this->assertEquals('GET', strtoupper($httpClient->getStack()[0]->request->getMethod()));
        $this->assertEquals('', (string) $httpClient->getStack()[0]->request->getBody());


        ////
        // test request with method headers and body
        $httpClient->resetStack();
        $request = RequestBuilder::buildRequest('http://foo.com', 'POST', ['User-Agent' => 'foobarua', 'x-foo' => 'bar'], 'a=b');

        $browser->sendRequest($request);

        $this->assertCount(1, $httpClient);
        $this->assertEquals('http://foo.com', (string) $httpClient->getStack()[0]->request->getUri());
        $this->assertEquals(
            [
                'User-Agent' => ['serps'],
                'Accept-Language' => ['en-US,en;q=0.8'],
                'x-foo' => ['bar'],
                'Host' => ['foo.com']
            ],
            $httpClient->getStack()[0]->request->getHeaders()
        );
        $this->assertEquals('POST', strtoupper($httpClient->getStack()[0]->request->getMethod()));
        $this->assertEquals('a=b', (string) $httpClient->getStack()[0]->request->getBody());


        ////
        // test request with method headers and body
        $browser = new Browser($httpClient, 'foo-browser'); // Set a browser with different UA
        $httpClient->resetStack();
        $request = RequestBuilder::buildRequest('http://foo.com', 'POST', ['User-Agent' => 'foobarua', 'x-foo' => 'bar'], 'a=b');

        $browser->sendRequest($request);

        $this->assertCount(1, $httpClient);
        $this->assertEquals('http://foo.com', (string) $httpClient->getStack()[0]->request->getUri());
        $this->assertEquals(
            [
                'User-Agent' => ['foo-browser'],
                'Accept-Language' => ['en-US,en;q=0.8'],
                'x-foo' => ['bar'],
                'Host' => ['foo.com']
            ],
            $httpClient->getStack()[0]->request->getHeaders()
        );
        $this->assertEquals('POST', strtoupper($httpClient->getStack()[0]->request->getMethod()));
        $this->assertEquals('a=b', (string) $httpClient->getStack()[0]->request->getBody());
    }

    public function testAccessors()
    {

        $userAgent= 'bar';
        $language = 'foo';
        $cookieJar = new ArrayCookieJar();
        $proxy = Proxy::createFromString('localhost:80');

        $browser = new Browser(new StackingHttpClient(), $userAgent, $language, $cookieJar, $proxy);

        $this->assertEquals('foo', $browser->getAcceptLanguage());
        $this->assertEquals('bar', $browser->getUserAgent());
        $this->assertSame($cookieJar, $browser->getCookieJar());
        $this->assertSame($proxy, $browser->getProxy());


        $cookieJar2 = new ArrayCookieJar();
        $proxy2 = Proxy::createFromString('localhost:90');
        $browser->setAcceptLanguage('baz');
        $browser->setUserAgent('qux');
        $browser->setCookieJar($cookieJar2);
        $browser->setProxy($proxy2);

        $this->assertEquals('baz', $browser->getAcceptLanguage());
        $this->assertEquals('qux', $browser->getUserAgent());
        $this->assertSame($cookieJar2, $browser->getCookieJar());
        $this->assertSame($proxy2, $browser->getProxy());

        $browser->setCookieJar(null);
        $browser->setProxy(null);
        $this->assertNull($browser->getCookieJar());
        $this->assertNull($browser->getProxy());
    }
}
