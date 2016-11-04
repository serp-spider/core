<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Psr7\Proxy;

use Psr\Http\Message\RequestInterface;
use Serps\Core\Psr7\RequestBuilder;
use Zend\Diactoros\Stream;

use Zend\Diactoros\Request as DiactorosRequest;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class RequestBuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider requestProvider
     */
    public function testBuildEmptyRequestRequest($builder, $expectedClass, $url = null, $method = null, $headers = [], $body = null)
    {
        $body = new Stream('php://temp', 'r+');
        $body->write($body ? $body : '');
        $expectedRequest = new DiactorosRequest($url, $method ? $method : 'GET', $body, $headers);

        $testedRequest = $builder->invokeArgs(null, [$url, $method, $headers, $body]);

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
        $diactorosBuilder = $this->getBuilderMethod('requestFromZendDiactoros');
        $guzzlebuilder    = $this->getBuilderMethod('requestFromGuzzlePSR7');
        $globalBuilder    = $this->getBuilderMethod('buildRequest');


        return [
            [$globalBuilder, DiactorosRequest::class],
            [$guzzlebuilder, GuzzleRequest::class],
            [$diactorosBuilder, DiactorosRequest::class],

            [$globalBuilder, DiactorosRequest::class, 'http://example.com', 'POST', ['User-Agent' => 'foobar'], 'a=b&c=d'],
            [$guzzlebuilder, GuzzleRequest::class, 'http://example.com', 'POST', ['User-Agent' => 'foobar'], 'a=b&c=d'],
            [$diactorosBuilder, DiactorosRequest::class, 'http://example.com', 'POST', ['User-Agent' => 'foobar'], 'a=b&c=d']
        ];
    }
}
