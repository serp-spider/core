<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Cookie;

use Serps\Core\Cookie\SetCookieString;

/**
 * @covers Serps\Core\Cookie\SetCookieString
 * @covers Serps\Core\Cookie\Cookie
 */
class SetCookieStringTest extends \PHPUnit_Framework_TestCase
{

    public function testParseCookie()
    {

        $expiresTime = time();
        $cookieString = 'foo=bar; path=/; domain=.foo.com;';
        $cookieString .=' expires=' . gmdate('D, d M Y H:i:s T', $expiresTime);
        
        $cookie = SetCookieString::parse($cookieString, 'foo.com', '/bar');

        $this->assertEquals('foo', $cookie->getName());
        $this->assertEquals('bar', $cookie->getValue());

        $this->assertEquals($expiresTime, $cookie->getExpires());
        $this->assertEquals('.foo.com', $cookie->getDomain());


        // No path with given path empty
        $cookieString = 'foo=bar;';
        $cookie = SetCookieString::parse($cookieString, 'foo.com', '');

        $this->assertEquals('/', $cookie->getPath());


        // No path with given path '/'
        $cookieString = 'foo=bar;';
        $cookie = SetCookieString::parse($cookieString, 'foo.com', '/');

        $this->assertEquals('/', $cookie->getPath());


        // No path with given path '/bar'
        $cookieString = 'foo=bar;';
        $cookie = SetCookieString::parse($cookieString, 'foo.com', '/bar');

        $this->assertEquals('/', $cookie->getPath());


        // No path with given path 'bar'
        $cookieString = 'foo=bar;';
        $cookie = SetCookieString::parse($cookieString, 'foo.com', 'bar');

        $this->assertEquals('/', $cookie->getPath());


        // No path with given path '/bar/'
        $cookieString = 'foo=bar;';
        $cookie = SetCookieString::parse($cookieString, 'foo.com', '/bar/');

        $this->assertEquals('/bar', $cookie->getPath());
    }
}
