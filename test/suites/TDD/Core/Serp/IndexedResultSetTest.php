<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\TDD\Core;

use Serps\Core\Serp\BaseResult;
use Serps\Core\Serp\IndexedResultSet;

/**
 * @covers Serps\Core\Serp\IndexedResultSet
 */
class IndexedResultSetTest extends \PHPUnit_Framework_TestCase
{

    public function testAddItem()
    {
        $resultSet = new IndexedResultSet(1);

        $resultSet->addItem(new BaseResult('classical', []));
        $this->assertCount(1, $resultSet->getItems());
        $this->assertEquals(1, $resultSet->getItems()[0]->getRealPosition());
        $this->assertEquals(1, $resultSet->getItems()[0]->getOnPagePosition());

        $resultSet->addItem(new BaseResult('classical', []));
        $this->assertCount(2, $resultSet->getItems());
        $this->assertEquals(2, $resultSet->getItems()[1]->getRealPosition());
        $this->assertEquals(2, $resultSet->getItems()[1]->getOnPagePosition());


        $resultSet = new IndexedResultSet(11);

        $resultSet->addItem(new BaseResult('classical', []));
        $this->assertCount(1, $resultSet->getItems());
        $this->assertEquals(11, $resultSet->getItems()[0]->getRealPosition());
        $this->assertEquals(1, $resultSet->getItems()[0]->getOnPagePosition());

        $resultSet->addItem(new BaseResult('classical', []));
        $this->assertCount(2, $resultSet->getItems());
        $this->assertEquals(12, $resultSet->getItems()[1]->getRealPosition());
        $this->assertEquals(2, $resultSet->getItems()[1]->getOnPagePosition());
    }

    public function testGetResultsByType()
    {
        $resultSet = new IndexedResultSet(1);

        $resultSet->addItem(new BaseResult('classical', []));
        $resultSet->addItem(new BaseResult('image', []));
        $resultSet->addItem(new BaseResult('classical', []));

        $classical = $resultSet->getResultsByType('classical');
        $this->assertCount(2, $classical);
        $this->assertEquals(1, $classical[0]->getRealPosition());
        $this->assertEquals(1, $classical[0]->getOnPagePosition());
        $this->assertEquals(3, $classical[1]->getRealPosition());
        $this->assertEquals(3, $classical[1]->getOnPagePosition());


        $imageResults = $resultSet->getResultsByType('image');
        $this->assertCount(1, $imageResults);
        $this->assertEquals(2, $imageResults[0]->getRealPosition());
        $this->assertEquals(2, $imageResults[0]->getOnPagePosition());

        $videoResults = $resultSet->getResultsByType('video');
        $this->assertCount(0, $videoResults);

        $classAndImageResults = $resultSet->getResultsByType('classical', 'image');
        $this->assertCount(3, $classAndImageResults);
    }

    public function testHasType()
    {
        $resultSet = new IndexedResultSet(1);

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
