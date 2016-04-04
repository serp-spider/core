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

        $this->assertEquals('1.1.1.1', $proxy->getIp());
        $this->assertEquals(80, $proxy->getPort());
        $this->assertEquals('user', $proxy->getUser());
        $this->assertEquals('pswd', $proxy->getPassword());
        $this->assertEquals(null, $proxy->getScheme());
        $this->assertEquals('user:pswd@1.1.1.1:80', $proxy->__toString());
    }

    public function testScheme()
    {
        $proxy = new Proxy('1.1.1.1', 80, 'user', 'pswd', 'http');
        $this->assertEquals('http', $proxy->getScheme());
        $this->assertEquals('http://user:pswd@1.1.1.1:80', $proxy->__toString());

        $proxy = new Proxy('1.1.1.1', 80, 'user', 'pswd', 'https');
        $this->assertEquals('https', $proxy->getScheme());
        $this->assertEquals('https://user:pswd@1.1.1.1:80', $proxy->__toString());
    }

    public function testFromString()
    {
        $proxy = Proxy::createFromString('user:pswd@1.1.1.1:80');

        $this->assertEquals('1.1.1.1', $proxy->getIp());
        $this->assertEquals(80, $proxy->getPort());
        $this->assertEquals('user', $proxy->getUser());
        $this->assertEquals('pswd', $proxy->getPassword());

        $proxy = Proxy::createFromString('1.1.1.1:80');
        $this->assertEquals('1.1.1.1', $proxy->getIp());
        $this->assertEquals(80, $proxy->getPort());
        $this->assertEquals(null, $proxy->getUser());
        $this->assertEquals(null, $proxy->getPassword());
    }

    public function testToString()
    {
        $proxy = Proxy::createFromString('user:pswd@1.1.1.1:80');
        $proxyString = (string)$proxy;
        $this->assertEquals('user:pswd@1.1.1.1:80', $proxyString);

        $proxy = Proxy::createFromString('1.1.1.1:80');
        $proxyString = (string)$proxy;
        $this->assertEquals('1.1.1.1:80', $proxyString);

        $proxy = Proxy::createFromString('user@1.1.1.1:80');
        $proxyString = (string)$proxy;
        $this->assertEquals('user@1.1.1.1:80', $proxyString);
    }
}
