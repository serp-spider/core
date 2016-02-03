<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\TDD\Core;

use Serps\Core\Serp\BaseResult;
use Serps\Core\Serp\ItemPosition;

/**
 * @covers Serps\Core\Serp\ItemPosition
 */
class ItemPositionTest extends \PHPUnit_Framework_TestCase
{

    public function testGetPosition()
    {
        $itemPosition = new ItemPosition(
            1,
            new BaseResult('classical', [])
        );
        $this->assertEquals(1, $itemPosition->getPosition());
    }

    public function testGetType()
    {
        $itemPosition = new ItemPosition(
            1,
            new BaseResult('classical', [])
        );
        $this->assertEquals('classical', $itemPosition->getType());
    }

    public function testIs()
    {
        $itemPosition = new ItemPosition(
            1,
            new BaseResult('classical', [])
        );
        $this->assertTrue($itemPosition->is('classical'));
    }

    public function testGetData()
    {
        $itemPosition = new ItemPosition(
            1,
            new BaseResult('classical', ['foo' => 'bar'])
        );
        $this->assertEquals(['foo' => 'bar'], $itemPosition->getData());
    }

    public function testGetDataValue()
    {
        $itemPosition = new ItemPosition(
            1,
            new BaseResult('classical', ['foo' => 'bar'])
        );
        $this->assertEquals('bar', $itemPosition->getDataValue('foo'));
    }
}
