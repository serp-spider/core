<?php
/**
 * @license see LICENSE
 */
namespace Serps\Test\TDD\Core;

use Serps\Core\Cookie\Cookie;
use Serps\Core\Url;
use Serps\Core\UrlArchive;

/**
 * @covers Serps\Core\Url
 * @covers Serps\Core\UrlArchive
 */
class UrlTest extends \PHPUnit_Framework_TestCase
{



    public function testGetUrl()
    {
        $builder = new Url('example.com');
        $this->assertEquals('https://example.com', $builder->buildUrl());

        $builder->setHash('foo');
        $this->assertEquals('https://example.com#foo', $builder->buildUrl());

        $builder->setParam('foo', 'bar');
        $builder->setParam('foobar', 'foo bar');
        $this->assertEquals('https://example.com?foo=bar&foobar=foo+bar#foo', $builder->buildUrl());

        $builder->setPath('some/path');
        $this->assertEquals('https://example.com/some/path?foo=bar&foobar=foo+bar#foo', $builder->buildUrl());

        $builder->setScheme('http');
        $this->assertEquals('http://example.com/some/path?foo=bar&foobar=foo+bar#foo', $builder->buildUrl());


    }

    public function testSetParam()
    {
        $builder = new Url('example');
        $this->assertEquals('', $builder->getQueryString());

        $builder->setParam('foo', 'bar');
        $this->assertEquals('foo=bar', $builder->getQueryString());

        $builder->setParam('foo', 'baz');
        $this->assertEquals('foo=baz', $builder->getQueryString());

        $builder->setParam('foobar', 'foo bar');
        $this->assertEquals('foo=baz&foobar=foo+bar', $builder->getQueryString());

        $builder->setParam('foobar', 'foo bar', true);
        $this->assertEquals('foo=baz&foobar=foo bar', $builder->getQueryString());
    }

    public function testGetParamValue()
    {
        $builder = new Url('example');

        $this->assertNull($builder->getParamValue('q'));
        $this->assertEquals('foo', $builder->getParamValue('q', 'foo'));

        $builder->setParam('q', 'bar');
        $this->assertEquals('bar', $builder->getParamValue('q', 'foo'));
    }

    public function testRemoveParam()
    {
        $builder = new Url('example');
        $this->assertEquals('', $builder->getQueryString());

        $builder->setParam('foo', 'bar');
        $builder->setParam('foobar', 'foo bar');
        $this->assertEquals('foo=bar&foobar=foo+bar', $builder->getQueryString());

        $builder->removeParam('foo');
        $this->assertEquals('foobar=foo+bar', $builder->getQueryString());
    }

    public function testSetHost()
    {
        $builder = new Url('example');
        $this->assertEquals('example', $builder->getHost());
        $builder->setHost('google.com');
        $this->assertEquals('google.com', $builder->getHost());
    }

    public function testGetParams()
    {
        $builder = new Url('example');
        $this->assertEquals([], $builder->getParams());

        $builder->setParam('foo', 'bar');
        $this->assertCount(1, $builder->getParams());
        $this->assertArrayHasKey('foo', $builder->getParams());
        $this->assertEquals('bar', $builder->getParams()['foo']->getValue());
    }

    public function testFromString()
    {
        $url = Url::fromString('https://foo/bar?qux=baz');

        $this->assertInstanceOf(Url::class, $url);
        $this->assertEquals('https://foo/bar?qux=baz', $url->buildUrl());
    }


    public function testResolve()
    {
        $url = Url::fromString('https://foo/bar?qux=baz');
        $urlArchive = UrlArchive::fromString('https://foo/bar?qux=baz');

        $newUrl = $url->resolve('//bar');
        $this->assertEquals('https://bar', $newUrl->buildUrl());
        $this->assertInstanceOf(Url::class, $newUrl);

        $newUrl = $urlArchive->resolve('//bar');
        $this->assertEquals('https://bar', $newUrl->buildUrl());
        $this->assertInstanceOf(UrlArchive::class, $newUrl);

        $newUrl = $url->resolve('/baz');
        $this->assertEquals('https://foo/baz', $newUrl->buildUrl());

        $newUrl = $url->resolve('http://baz/foo');
        $this->assertEquals('http://baz/foo', $newUrl->buildUrl());
    }

    public function testResolveAs()
    {
        $url = Url::fromString('https://foo/bar?qux=baz');

        $newUrl = $url->resolve('//bar', UrlArchive::class);
        $this->assertEquals('https://bar', $newUrl->buildUrl());
        $this->assertInstanceOf(UrlArchive::class, $newUrl);

        $newUrl = $url->resolve('//bar', Url::class);
        $this->assertEquals('https://bar', $newUrl->buildUrl());
        $this->assertInstanceOf(UrlArchive::class, $newUrl);
    }

    public function testResolveAsBadType()
    {
        $url = Url::fromString('https://foo/bar?qux=baz');

        $this->setExpectedException(\InvalidArgumentException::class);
        $url->resolve('//bar', []);
    }

    public function testResolveAsBadClass()
    {
        $url = Url::fromString('https://foo/bar?qux=baz');

        $this->setExpectedException(\InvalidArgumentException::class);
        $url->resolve('//bar', Cookie::class);
    }
}
