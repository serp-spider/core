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

    public function testGetTypes()
    {
        $result = new BaseResult('classical', []);
        $this->assertEquals(['classical'], $result->getTypes());
    }

    public function testGetTypesMany()
    {

        $result = new BaseResult(['classical', 'video'], []);
        $this->assertEquals(['classical', 'video'], $result->getTypes());
    }

    public function testIs()
    {
        $result = new BaseResult('classical', []);
        $this->assertTrue($result->is('classical'));
        $this->assertTrue($result->is('video', 'classical'));
        $this->assertFalse($result->is('video', 'image'));
    }

    public function testGetData()
    {
        $result = new BaseResult('classical', [
            'foo' => 'bar',
            'bar' => function () {
                return 'baz';
            }
        ]);
        $this->assertEquals(['foo' => 'bar', 'bar' => 'baz'], $result->getData());
    }

    public function testNestedGetDataValue()
    {
        $barValue = new BaseResult('foo', [
            'bar' => 'baz',
            'foo' => 'qux'
        ]);

        $result = new BaseResult('classical', [
            'foo' => 'bar',
            'bar' => function () use ($barValue) {
                return $barValue;
            }
        ]);
        $this->assertSame($barValue, $result->getDataValue('bar'));
    }


    public function testNestedGetData()
    {
        $result = new BaseResult('classical', [
            'foo' => 'bar',
            'bar' => function () {
                return new BaseResult('foo', [
                    'bar' => 'baz',
                    'foo' => 'qux'
                ]);
            }
        ]);
        $this->assertEquals(['foo' => 'bar', 'bar' => ['bar' => 'baz', 'foo' => 'qux']], $result->getData());
    }

    public function testNestedInArrayGetData()
    {
        $result = new BaseResult('classical', [
            'foo' => 'bar',
            'bar' => function () {
                return [
                    new BaseResult('foo', [
                        'bar' => 'baz',
                        'foo' => 'qux'
                    ]),
                    new BaseResult('foo', [
                        'bar' => 'far',
                        'foo' => 'boz'
                    ]),
                ];
            }
        ]);
        $this->assertEquals([
            'foo' => 'bar',
            'bar' => [
                ['bar' => 'baz', 'foo' => 'qux'],
                ['bar' => 'far', 'foo' => 'boz'],
            ]
        ], $result->getData());
    }




    public function testGetDataValue()
    {
        $result = new BaseResult('classical', ['foo' => 'bar']);
        $this->assertEquals('bar', $result->getDataValue('foo'));
        $this->assertEquals('bar', $result->foo);
    }

    public function testGetDataValueCallable()
    {
        $result = new BaseResult('classical', ['foo' => function () {
            return 'bar';
        }]);
        $this->assertEquals('bar', $result->getDataValue('foo'));
    }

    public function testGetDataValueCallableWithDependencies()
    {
        $result = new BaseResult('classical', [
            'foo' => function (BaseResult $result) {
                return $result->getDataValue('bar') . '-bar';
            },
            'bar' => function () {
                return 'qux';
            }
        ]);
        $this->assertEquals('qux-bar', $result->getDataValue('foo'));
    }

    /**
     * covers a bug where string values are considered to be callable
     *
     * @link https://github.com/serp-spider/search-engine-google/issues/71
     */
    public function testGetDataValueStringCallable()
    {
        $result = new BaseResult('classical', ['foo' => 'strpos']);
        $this->assertEquals('strpos', $result->getDataValue('foo'));
    }
}
