<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Cookie;

use Serps\Core\Cookie\ArrayCookieJar;
use Serps\Core\Cookie\Cookie;
use Zend\Diactoros\Request;

/**
 * @covers Serps\Core\Cookie\ArrayCookieJar
 * @covers Serps\Core\Cookie\Cookie
 */
class ArrayCookieJarTest extends \PHPUnit_Framework_TestCase
{

    public function testSet()
    {
        $cookieJar = new ArrayCookieJar();

        $cookie = new Cookie('foo', 'bar', []);
        $cookieJar->set($cookie);
        $this->assertCount(0, $cookieJar->all());

        $cookie = new Cookie('foo', 'bar', ['domain' => 'foo.bar']);
        $cookieJar->set($cookie);
        $this->assertCount(1, $cookieJar->all());
        $this->assertSame($cookie, $cookieJar->all(null, null, 'foo')[0]);

        $cookie = new Cookie('baz', 'bar', ['domain' => 'foo.bar']);
        $cookieJar->set($cookie);
        $this->assertCount(2, $cookieJar->all());

        $cookie = new Cookie('foo', 'baz', ['domain' => 'foo.bar']);
        $cookieJar->set($cookie);
        $this->assertCount(2, $cookieJar->all());
        $this->assertSame($cookie, $cookieJar->all(null, null, 'foo')[0]);

        $cookie = new Cookie('foo', 'bar', ['domain' => 'foo.bar.com']);
        $cookieJar->set($cookie);
        $this->assertCount(3, $cookieJar->all());
        $this->assertCount(2, $cookieJar->all(null, null, 'foo'));
        $this->assertSame($cookie, $cookieJar->all('foo.bar.com', null, 'foo')[0]);
    }

    public function testRemove()
    {
        $cookieJar = new ArrayCookieJar();

        // Remove all
        $cookie = new Cookie('foo', 'bar', ['domain' => 'foo.bar']);
        $cookieJar->set($cookie);
        $this->assertCount(1, $cookieJar->all());

        $cookieJar->remove();
        $this->assertCount(0, $cookieJar->all());


        // Remove domain
        $cookie = new Cookie('foo', 'bar', ['domain' => 'foo.bar']);
        $cookieJar->set($cookie);
        $this->assertCount(1, $cookieJar->all());

        $cookie = new Cookie('foo', 'bar', ['domain' => 'foo.bar.baz']);
        $cookieJar->set($cookie);
        $this->assertCount(2, $cookieJar->all());

        $cookieJar->remove('foo.bar');
        $this->assertCount(1, $cookieJar->all());
        $this->assertSame($cookie, $cookieJar->all()[0]);


        // remove name
        $cookieJar->remove(null, null, 'foo');
        $this->assertCount(0, $cookieJar->all());

        $cookie = new Cookie('foo', 'bar', ['domain' => 'foo.bar.baz']);
        $cookieJar->set($cookie);

        $this->assertCount(1, $cookieJar->all());
        $cookieJar->remove('foo.bar.baz', null, 'foo');
        $this->assertCount(0, $cookieJar->all());


        // remove path
        $cookie = new Cookie('foo', 'bar', ['domain' => 'foo.bar.baz', 'path' => '/foo']);
        $cookieJar->set($cookie);

        $this->assertCount(1, $cookieJar->all());
        $cookieJar->remove(null, '/bar');
        $this->assertCount(1, $cookieJar->all());
        $cookieJar->remove(null, '/foo');
        $this->assertCount(0, $cookieJar->all());
    }

    public function testRemoveTemporary()
    {
        $cookieJar = new ArrayCookieJar();

        $cookie = new Cookie('foo', 'bar', ['domain' => 'foo.bar']);
        $cookieJar->set($cookie);

        $cookie = new Cookie('foo', 'bar', ['domain' => 'foo.bar.baz', 'expires' => time() + 1000]);
        $cookieJar->set($cookie);

        $this->assertCount(2, $cookieJar->all());
        $cookieJar->removeTemporary();
        $this->assertCount(1, $cookieJar->all());

        $this->assertSame($cookie, $cookieJar->all()[0]);

        // expired should not be removed
        $cookie = new Cookie('foo', 'bar', ['domain' => 'foo.bar', 'expires' => time() -1000]);
        $cookieJar->set($cookie);
        $this->assertCount(2, $cookieJar->all(null, null, null, false, false));
        $cookieJar->removeTemporary();
        $this->assertCount(2, $cookieJar->all(null, null, null, false, false));
    }

    public function testRemoveExpired()
    {
        $cookieJar = new ArrayCookieJar();

        $cookie = new Cookie('foo', 'bar', ['domain' => 'foo.bar', 'expires' => time() -1000]);
        $cookieJar->set($cookie);

        $cookie = new Cookie('foo', 'bar', ['domain' => 'foo.bar.baz', 'expires' => time() + 1000]);
        $cookieJar->set($cookie);

        $this->assertCount(2, $cookieJar->all(null, null, null, false, false));
        $cookieJar->removeExpired();
        $this->assertCount(1, $cookieJar->all(null, null, null, false, false));

        $this->assertSame($cookie, $cookieJar->all()[0]);

        // temporary should not be removed
        $cookie = new Cookie('foo', 'bar', ['domain' => 'baz.foo.bar']);
        $cookieJar->set($cookie);
        $this->assertCount(2, $cookieJar->all(null, null, null, false, false));
        $cookieJar->removeExpired();
        $this->assertCount(2, $cookieJar->all(null, null, null, false, false));
    }

    public function testGetMatchingCookies()
    {
        $cookieJar = new ArrayCookieJar();

        $cookie = new Cookie('foo', 'bar', ['domain' => 'foo.bar', 'expires' => time() + 1000]);
        $cookieJar->set($cookie);

        $cookie = new Cookie('foo', 'bar', ['domain' => 'foo.bar.baz', 'expires' => time() + 1000]);
        $cookieJar->set($cookie);

        $request = new Request('http://foo.bar');

        $matchingCookies = $cookieJar->getMatchingCookies($request);

        $this->assertCount(1, $matchingCookies);
        $this->assertEquals('foo.bar', $matchingCookies[0]->getDomain());
    }

    public function testExport()
    {
        $cookieJar = new ArrayCookieJar();

        $cookieJar->set(new Cookie('foo', 'bar', [
            'path'    => '/foo',
            'domain'  => 'foo.bar',
            'expires' => '123',
            'discard' => true,
            'secure'  => true,
            'http_only' => false,
        ]));

        $cookieJar->set(new Cookie('bar', 'baz', [
            'path'    => '/baz',
            'domain'  => 'foo.baz',
            'expires' => '321',
            'discard' => true,
            'secure'  => true,
            'http_only' => true,
        ]));

        $this->assertEquals(
            [
                [
                    'name' => 'foo',
                    'value' => 'bar',
                    'flags' =>  [
                        'path'    => '/foo',
                        'domain'  => 'foo.bar',
                        'expires' => '123',
                        'discard' => true,
                        'secure'  => true,
                        'http_only' => false,
                    ]
                ],
                [
                    'name' => 'bar',
                    'value' => 'baz',
                    'flags' =>  [
                        'path'    => '/baz',
                        'domain'  => 'foo.baz',
                        'expires' => '321',
                        'discard' => true,
                        'secure'  => true,
                        'http_only' => true,
                    ]
                ]
            ],
            $cookieJar->export()
        );
    }
}
