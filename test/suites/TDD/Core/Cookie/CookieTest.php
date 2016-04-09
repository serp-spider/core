<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Cookie;

use Serps\Core\Cookie\Cookie;

/**
 * @covers Serps\Core\Cookie\Cookie
 */
class CookieTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return Cookie
     */
    private function defaultCookie()
    {
        return new Cookie('foo', 'bar', [
            'path'    => '/',
            'domain'  => '.baz.com',
            'expires' => time() + 10000
        ]);
    }

    public function testMatchesPath()
    {
        $cookie = $this->defaultCookie();
        $this->assertTrue($cookie->matchesPath('/foo'));
        $this->assertTrue($cookie->matchesPath('/'));
        $this->assertFalse($cookie->matchesPath(''));

        $cookie = new Cookie('foo', 'bar', []);
        $this->assertTrue($cookie->matchesPath('/foo'));
        $this->assertTrue($cookie->matchesPath('/'));
        $this->assertFalse($cookie->matchesPath(''));

        $cookie = new Cookie('foo', 'bar', [
            'path' => '/baz'
        ]);
        $this->assertFalse($cookie->matchesPath('/foo'));
        $this->assertFalse($cookie->matchesPath('/'));
        $this->assertFalse($cookie->matchesPath(''));
        $this->assertTrue($cookie->matchesPath('/baz'));
        $this->assertTrue($cookie->matchesPath('/baz/foo'));
    }

    public function testMatchesDomain()
    {
        $cookie = $this->defaultCookie();
        $this->assertTrue($cookie->matchesDomain('baz.com'));
        $this->assertTrue($cookie->matchesDomain('bar.baz.com'));
        $this->assertTrue($cookie->matchesDomain('foo.bar.baz.com'));
        $this->assertFalse($cookie->matchesDomain('foo.bar.baz.com.au'));

        $cookie = new Cookie('foo', 'bar', [
            'domain' => 'baz.com'
        ]);
        $this->assertTrue($cookie->matchesDomain('baz.com'));
        $this->assertTrue($cookie->matchesDomain('bar.baz.com'));
        $this->assertTrue($cookie->matchesDomain('foo.bar.baz.com'));
        $this->assertFalse($cookie->matchesDomain('foo.bar.baz.com.au'));

        $cookie = new Cookie('foo', 'bar', [
            'domain' => 'bar.baz.com'
        ]);
        $this->assertFalse($cookie->matchesDomain('baz.com'));
        $this->assertTrue($cookie->matchesDomain('bar.baz.com'));
        $this->assertFalse($cookie->matchesDomain('foo.baz.com'));
        $this->assertTrue($cookie->matchesDomain('foo.bar.baz.com'));
        $this->assertFalse($cookie->matchesDomain('foo.bar.baz.com.au'));
    }

    public function testIsExpired()
    {
        $this->assertFalse($this->defaultCookie()->isExpired());

        $cookie = new Cookie('foo', 'bar', [
            'expires' => time() - 10000
        ]);
        $this->assertTrue($cookie->isExpired());

        $cookie = new Cookie('foo', 'bar', []);
        $this->assertFalse($cookie->isExpired());
    }

    public function testValidate()
    {
        $cookie = new Cookie('foo', 'bar', ['domain' => 'foo.bar']);
        $this->assertTrue($cookie->validate());

        // Cookie Name Empty
        $cookie = new Cookie('', 'bar', ['domain' => 'foo.bar']);
        $this->assertNotTrue($cookie->validate());

        // Domain empty
        $cookie = new Cookie('foo', 'bar', ['domain' => '']);
        $this->assertNotTrue($cookie->validate());

        // Cookie name invalid
        $cookie = new Cookie('fo o', 'bar', ['domain' => 'foo.bar']);
        $this->assertNotTrue($cookie->validate());
        $cookie = new Cookie('fo\\o', 'bar', ['domain' => 'foo.bar']);
        $this->assertNotTrue($cookie->validate());
    }

    public function testExport()
    {
        $cookie = new Cookie('foo', 'bar', [
            'path'    => '/foo',
            'domain'  => 'foo.bar',
            'expires' => '123',
            'discard' => true,
            'secure'  => true,

        ]);

        $this->assertEquals(
            [
                'name' => 'foo',
                'value' => 'bar',
                'flags' =>  [
                    'path'    => '/foo',
                    'domain'  => 'foo.bar',
                    'expires' => '123',
                    'discard' => true,
                    'secure'  => true,

                ]
            ],
            $cookie->export()
        );
    }
}
