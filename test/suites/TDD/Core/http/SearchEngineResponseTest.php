<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Http\Proxy;

use Serps\Core\Http\SearchEngineResponse;
use Serps\Core\UrlArchive;

class SearchEngineResponseTest extends \PHPUnit_Framework_TestCase
{


    protected function getResponse()
    {
        return new SearchEngineResponse(
            ['foo' => 'bar', 'bar' => 'baz'],
            200,
            '<html></html>',
            true,
            UrlArchive::fromString('http://foo.bar'),
            UrlArchive::fromString('http://foo.bar'),
            ['baz' => 'qux', 'quux' => 'baz'],
            null
        );
    }

    public function testGetHeader()
    {
        $response = $this->getResponse();
        $this->assertEquals('bar', $response->getHeader('foo'));
        $this->assertEquals('baz', $response->getHeader('bar'));
        $this->assertNull($response->getHeader('fake'));
    }
}
