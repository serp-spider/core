<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Http\Proxy;

use Serps\Core\Http\Proxy;

/**
 * @covers Serps\Core\Http\Proxy
 */
class ProxyTest extends \PHPUnit_Framework_TestCase
{

    public function testProxy()
    {
        $proxy = new Proxy('1.1.1.1', 80, 'user', 'pswd');

        $this->assertEquals('1.1.1.1', $proxy->getHost());
        $this->assertEquals(80, $proxy->getPort());
        $this->assertEquals('user', $proxy->getUser());
        $this->assertEquals('pswd', $proxy->getPassword());
        $this->assertEquals('HTTP', $proxy->getType());
        $this->assertEquals('http://user:pswd@1.1.1.1:80', $proxy->__toString());
    }

    public function testType()
    {
        $proxy = new Proxy('1.1.1.1', 80, 'user', 'pswd', 'http');
        $this->assertEquals('HTTP', $proxy->getType());
        $this->assertEquals('http://user:pswd@1.1.1.1:80', $proxy->__toString());

        $proxy = new Proxy('1.1.1.1', 80, 'user', 'pswd', 'https');
        $this->assertEquals('HTTPS', $proxy->getType());
        $this->assertEquals('https://user:pswd@1.1.1.1:80', $proxy->__toString());
    }

    public function testFromString()
    {
        $proxy = Proxy::createFromString('user:pswd@1.1.1.1:80');

        $this->assertEquals('1.1.1.1', $proxy->getHost());
        $this->assertEquals(80, $proxy->getPort());
        $this->assertEquals('user', $proxy->getUser());
        $this->assertEquals('pswd', $proxy->getPassword());
        $this->assertEquals('HTTP', $proxy->getType());

        $proxy = Proxy::createFromString('1.1.1.1:80');
        $this->assertEquals('1.1.1.1', $proxy->getHost());
        $this->assertEquals(80, $proxy->getPort());
        $this->assertEquals(null, $proxy->getUser());
        $this->assertEquals(null, $proxy->getPassword());
        $this->assertEquals('HTTP', $proxy->getType());

        $proxy = Proxy::createFromString('https://1.1.1.1:80');
        $this->assertEquals('1.1.1.1', $proxy->getHost());
        $this->assertEquals(80, $proxy->getPort());
        $this->assertEquals(null, $proxy->getUser());
        $this->assertEquals(null, $proxy->getPassword());
        $this->assertEquals('HTTPS', $proxy->getType());

        $proxy = Proxy::createFromString('socks5://1.1.1.1:80');
        $this->assertEquals('1.1.1.1', $proxy->getHost());
        $this->assertEquals(80, $proxy->getPort());
        $this->assertEquals(null, $proxy->getUser());
        $this->assertEquals(null, $proxy->getPassword());
        $this->assertEquals('SOCKS5', $proxy->getType());
    }

    public function testToString()
    {
        $proxy = Proxy::createFromString('user:pswd@1.1.1.1:80');
        $proxyString = (string)$proxy;
        $this->assertEquals('http://user:pswd@1.1.1.1:80', $proxyString);

        $proxy = Proxy::createFromString('1.1.1.1:80');
        $proxyString = (string)$proxy;
        $this->assertEquals('http://1.1.1.1:80', $proxyString);

        $proxy = Proxy::createFromString('user@1.1.1.1:80');
        $proxyString = (string)$proxy;
        $this->assertEquals('http://user@1.1.1.1:80', $proxyString);
    }
}
