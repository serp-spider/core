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

        $cookieString = 'foo=bar; path=/; domain=.foo.com; expires=Tue, 01-Jan-2050 08:00:00 GMT';
        $cookie = SetCookieString::parse($cookieString, 'foo.com', '/bar');

        $this->assertEquals('foo', $cookie->getName());
        $this->assertEquals('bar', $cookie->getValue());

        $this->assertEquals('Tue, 01-Jan-2050 08:00:00 GMT', $cookie->getExpire());
        $this->assertEquals('.foo.com', $cookie->getDomain());

    }
}
