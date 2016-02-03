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
        $resultSet = new ResultSet(1);

        $resultSet->addItem(new BaseResult('classical', []));
        $this->assertCount(1, $resultSet->getItems());
        $this->assertEquals(1, $resultSet->getItems()[0]->getPosition());

        $resultSet->addItem(new BaseResult('classical', []));
        $this->assertCount(2, $resultSet->getItems());
        $this->assertEquals(2, $resultSet->getItems()[1]->getPosition());


        $resultSet = new ResultSet(10);

        $resultSet->addItem(new BaseResult('classical', []));
        $this->assertCount(1, $resultSet->getItems());
        $this->assertEquals(10, $resultSet->getItems()[0]->getPosition());

        $resultSet->addItem(new BaseResult('classical', []));
        $this->assertCount(2, $resultSet->getItems());
        $this->assertEquals(11, $resultSet->getItems()[1]->getPosition());
    }

    public function testGetResultsByType()
    {
        $resultSet = new ResultSet(1);

        $resultSet->addItem(new BaseResult('classical', []));
        $resultSet->addItem(new BaseResult('image', []));
        $resultSet->addItem(new BaseResult('classical', []));

        $classical = $resultSet->getResultsByType('classical');
        $this->assertCount(2, $classical);
        $this->assertEquals(1, $classical[0]->getPosition());
        $this->assertEquals(3, $classical[1]->getPosition());


        $imageResults = $resultSet->getResultsByType('image');
        $this->assertCount(1, $imageResults);
        $this->assertEquals(2, $imageResults[0]->getPosition());

        $videoResults = $resultSet->getResultsByType('video');
        $this->assertCount(0, $videoResults);
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
    }
}
