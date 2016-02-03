<?php
/**
 * @license see LICENSE
 */
namespace Serps\Test\TDD\Core;

use Serps\Core\Url;

/**
 * @covers Serps\Core\Url
 * @covers Serps\Core\UrlArchive
 */
class UrlTest extends \PHPUnit_Framework_TestCase
{



    public function testGetUrl()
    {
        $builder = new Url('example.com');
        $this->assertEquals('https://example.com', $builder->getUrl());

        $builder->setHash('foo');
        $this->assertEquals('https://example.com#foo', $builder->getUrl());

        $builder->setParam('foo', 'bar');
        $builder->setParam('foobar', 'foo bar');
        $this->assertEquals('https://example.com?foo=bar&foobar=foo+bar#foo', $builder->getUrl());

        $builder->setPath('some/path');
        $this->assertEquals('https://example.com/some/path?foo=bar&foobar=foo+bar#foo', $builder->getUrl());

        $builder->setScheme('http');
        $this->assertEquals('http://example.com/some/path?foo=bar&foobar=foo+bar#foo', $builder->getUrl());


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
        $this->assertEquals('https://foo/bar?qux=baz', $url->getUrl());
    }
}
