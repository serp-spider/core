<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\TDD\Core;

use Serps\Core\Serp\BaseResult;

/**
 * @covers Serps\Core\Serp\BaseResult
 */
class BaseResultTest extends \PHPUnit_Framework_TestCase
{

    public function testGetType()
    {
        $result = new BaseResult('classical', []);
        $this->assertEquals('classical', $result->getType());
    }

    public function testIs()
    {
        $result = new BaseResult('classical', []);
        $this->assertTrue($result->is('classical'));
    }

    public function testGetData()
    {
        $result = new BaseResult('classical', ['foo' => 'bar']);
        $this->assertEquals(['foo' => 'bar'], $result->getData());
    }

    public function testGetDataValue()
    {
        $result = new BaseResult('classical', ['foo' => 'bar']);
        $this->assertEquals('bar', $result->getDataValue('foo'));
    }
}
