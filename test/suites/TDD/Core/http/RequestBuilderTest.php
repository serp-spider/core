<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Http\Proxy;

use Psr\Http\Message\RequestInterface;
use Serps\Core\Psr7\RequestBuilder;
use Zend\Diactoros\Stream;

use Zend\Diactoros\Request as DiactorosRequest;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

/**
 * @covers Serps\Core\Psr7\RequestBuilder
 */
class RequestBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider requestProvider
     */
    public function testBuildRequest(RequestInterface $testedRequest, RequestInterface $expectedRequest, $expectedClass)
    {
        $this->assertInstanceOf($expectedClass, $testedRequest);
        $this->assertEquals($expectedRequest->getMethod(), $testedRequest->getMethod());
        $this->assertEquals((string) $expectedRequest->getUri(), (string) $testedRequest->getUri());
        $this->assertEquals((string) $expectedRequest->getBody(), (string)$testedRequest->getBody());
        $this->assertEquals($expectedRequest->getHeaders(), $testedRequest->getHeaders());
    }

    /**
     * @param $method
     * @return \ReflectionMethod
     */
    protected function getBuilderMethod($method)
    {
        $class = new \ReflectionClass(RequestBuilder::class);
        $method = $class->getMethod($method);
        $method->setAccessible(true);
        return $method;
    }

    public function requestProvider()
    {

        $emptyRequest = new DiactorosRequest('', 'GET');

        $postBody = new Stream('php://temp', 'r+');
        $postBody->write('a=b&c=d');
        $postRequest = new DiactorosRequest('http://example.com', 'POST', $postBody, ['User-Agent' => 'foobar']);

        $diactorosBuilder = $this->getBuilderMethod('requestFromZendDiactoros');
        $guzzlebuilder    = $this->getBuilderMethod('requestFromGuzzlePSR7');


        return [
            [RequestBuilder::buildRequest(), $emptyRequest, DiactorosRequest::class],
            [$guzzlebuilder->invokeArgs(null, []), $emptyRequest, GuzzleRequest::class],
            [$diactorosBuilder->invokeArgs(null, []), $emptyRequest, DiactorosRequest::class],

            [RequestBuilder::buildRequest('http://example.com', 'POST', ['User-Agent' => 'foobar'], 'a=b&c=d'), $postRequest, DiactorosRequest::class],
            [$guzzlebuilder->invokeArgs(null, ['http://example.com', 'POST', ['User-Agent' => 'foobar'], 'a=b&c=d']), $postRequest, GuzzleRequest::class],
            [$diactorosBuilder->invokeArgs(null, ['http://example.com', 'POST', ['User-Agent' => 'foobar'], 'a=b&c=d']), $postRequest, DiactorosRequest::class],
        ];
    }
}
