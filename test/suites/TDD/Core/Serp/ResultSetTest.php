<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\TDD\Core;

use Serps\Core\Serp\BaseResult;
use Serps\Core\Serp\ResultSet;

/**
 * @covers Serps\Core\Serp\ResultSet
 */
class ResultSetTest extends \PHPUnit_Framework_TestCase
{

    public function testAddItem()
    {
        $resultSet = new ResultSet();

        $item = new BaseResult('classical', []);
        $resultSet->addItem($item);
        $this->assertCount(1, $resultSet->getItems());
        $this->assertSame($item, $resultSet->getItems()[0]);

        $item = new BaseResult('classical', []);
        $resultSet->addItem($item);
        $this->assertCount(2, $resultSet->getItems());
        $this->assertSame($item, $resultSet->getItems()[1]);
    }

    public function testAddItems()
    {

        $resultSet = new ResultSet();

        $items = [
            new BaseResult('classical', []),
            new BaseResult('classical', [])
        ];

        $resultSet->addItems($items);

        $this->assertCount(2, $resultSet->getItems());
        $this->assertSame($items[0], $resultSet->getItems()[0]);
        $this->assertSame($items[1], $resultSet->getItems()[1]);
    }

    public function testGetResultsByType()
    {
        $resultSet = new ResultSet();

        $resultSet->addItem(new BaseResult('classical', []));
        $resultSet->addItem(new BaseResult('image', []));
        $resultSet->addItem(new BaseResult('classical', []));

        $classical = $resultSet->getResultsByType('classical');
        $this->assertCount(2, $classical);

        $imageResults = $resultSet->getResultsByType('image');
        $this->assertCount(1, $imageResults);

        $videoResults = $resultSet->getResultsByType('video');
        $this->assertCount(0, $videoResults);

        $classAndImageResults = $resultSet->getResultsByType('classical', 'image');
        $this->assertCount(3, $classAndImageResults);
    }

    public function testHasType()
    {
        $resultSet = new ResultSet();

        $resultSet->addItem(new BaseResult('classical', []));
        $resultSet->addItem(new BaseResult('image', []));
        $resultSet->addItem(new BaseResult('classical', []));

        $this->assertTrue($resultSet->hasType('classical'));
        $this->assertTrue($resultSet->hasType('image'));
        $this->assertFalse($resultSet->hasType('video'));

        $this->assertTrue($resultSet->hasType('video', 'image', 'classical'));
        $this->assertFalse($resultSet->hasType('video', 'ads'));
    }

    public function testArrayAccess()
    {
        $resultSet = new ResultSet();

        $item0 = new BaseResult('classical', []);

        $resultSet->addItem($item0);
        $resultSet->addItem(new BaseResult('image', []));
        $resultSet->addItem(new BaseResult('classical', []));

        $this->assertSame($item0, $resultSet[0]);
        $this->assertTrue(isset($resultSet[0]));
        $this->assertTrue(isset($resultSet[1]));
        $this->assertTrue(isset($resultSet[2]));
        $this->assertFalse(isset($resultSet[3]));

        try {
            $resultSet[0] = $item0;
            $this->fail('cannot add item');
        } catch (\Exception $e) {
        }

        try {
            unset($resultSet[0]);
            $this->fail('cannot unset item');
        } catch (\Exception $e) {
        }
    }
}
