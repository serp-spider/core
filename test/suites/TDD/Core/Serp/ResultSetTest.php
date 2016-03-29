<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\TDD\Core;

use Serps\Core\Serp\BaseResult;
use Serps\Core\Serp\ResultSet;

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

    public function testGetResultsByType()
    {
        $resultSet = new ResultSet(1);

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
        $resultSet = new ResultSet(1);

        $resultSet->addItem(new BaseResult('classical', []));
        $resultSet->addItem(new BaseResult('image', []));
        $resultSet->addItem(new BaseResult('classical', []));

        $this->assertTrue($resultSet->hasType('classical'));
        $this->assertTrue($resultSet->hasType('image'));
        $this->assertFalse($resultSet->hasType('video'));

        $this->assertTrue($resultSet->hasType('video', 'image', 'classical'));
        $this->assertFalse($resultSet->hasType('video', 'ads'));
    }
}
