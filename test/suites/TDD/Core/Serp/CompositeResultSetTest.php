<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\TDD\Core;

use Serps\Core\Serp\BaseResult;
use Serps\Core\Serp\CompositeResultSet;
use Serps\Core\Serp\IndexedResultSet;

/**
 * @covers Serps\Core\Serp\CompositeResultSet
 */
class CompositeResultSetTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return CompositeResultSet
     */
    public function getResultSet()
    {
        $compositeResultSet = new CompositeResultSet();

        $resultSet = new IndexedResultSet(1);
        $resultSet->addItem(new BaseResult('foo'));
        $resultSet->addItem(new BaseResult('bar'));
        $compositeResultSet->addResultSet($resultSet);

        $resultSet = new IndexedResultSet(1);
        $resultSet->addItem(new BaseResult('baz'));
        $resultSet->addItem(new BaseResult('foo'));
        $compositeResultSet->addResultSet($resultSet);

        return $compositeResultSet;
    }

    public function testConstruct()
    {
        $resultSet = new CompositeResultSet();
        $this->assertCount(0, $resultSet->getResultSets());

        $resultSet = new CompositeResultSet([
            new IndexedResultSet(1),
            new IndexedResultSet(1)
        ]);
        $this->assertCount(2, $resultSet->getResultSets());
    }

    public function testAddResultSet()
    {
        $resultSet = new CompositeResultSet();
        $resultSet->addResultSet(new IndexedResultSet(1));
        $this->assertCount(1, $resultSet->getResultSets());
        $resultSet->addResultSet(new IndexedResultSet(1));
        $this->assertCount(2, $resultSet->getResultSets());
    }

    public function testGetItems()
    {
        $resultSet = $this->getResultSet();

        $items = $resultSet->getItems();

        $this->assertCount(4, $items);

        $types = [];
        foreach ($items as $item) {
            $types[] = $item->getTypes()[0];
        }

        $this->assertEquals([
            'foo', 'bar', 'baz', 'foo'
        ], $types);
    }

    public function testGetResultsByType()
    {
        $resultSet = $this->getResultSet();

        $this->assertCount(2, $resultSet->getResultsByType('foo'));
        $this->assertCount(3, $resultSet->getResultsByType('foo', 'bar'));
        $this->assertCount(4, $resultSet->getResultsByType('foo', 'bar', 'baz'));
        $this->assertCount(1, $resultSet->getResultsByType('baz'));
        $this->assertCount(0, $resultSet->getResultsByType('fake'));
        $this->assertCount(2, $resultSet->getResultsByType('foo', 'fake'));
    }


    public function testHasType()
    {
        $resultSet = $this->getResultSet();

        $this->assertTrue($resultSet->hasType('foo'));
        $this->assertTrue($resultSet->hasType('foo', 'bar'));
        $this->assertTrue($resultSet->hasType('foo', 'bar', 'baz'));
        $this->assertTrue($resultSet->hasType('baz'));
        $this->assertFalse($resultSet->hasType('fake'));
        $this->assertTrue($resultSet->hasType('foo', 'fake'));
    }

    public function testCount()
    {
        $resultSet = $this->getResultSet();
        $this->assertCount(4, $resultSet);
    }

    public function testIteration()
    {
        $resultSet = $this->getResultSet();
        $types = [];
        foreach ($resultSet as $result) {
            $types[] = $result->getTypes()[0];
        }
        $this->assertEquals([
            'foo', 'bar', 'baz', 'foo'
        ], $types);
    }
}
