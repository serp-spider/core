<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\TDD\Core\Url;

use Serps\Core\Url\QueryParam;

/**
 * @covers Serps\Core\Url\QueryParam
 */
class QueryParamTest extends \PHPUnit_Framework_TestCase
{

    public function testIsRaw()
    {
        $queryParam = new QueryParam('foo', 'foo bar');
        $this->assertFalse($queryParam->isRaw());
        $queryParam = new QueryParam('foo', 'foo bar', true);
        $this->assertTrue($queryParam->isRaw());
    }

    public function testGetValue()
    {
        $queryParam = new QueryParam('foo', 'foo bar');
        $this->assertEquals('foo+bar', $queryParam->getValue());
        $queryParam = new QueryParam('foo', 'foo bar', true);
        $this->assertEquals('foo bar', $queryParam->getValue());
    }

    public function testGetRawValue()
    {
        $queryParam = new QueryParam('foo', 'foo bar');
        $this->assertEquals('foo bar', $queryParam->getRawValue());
        $queryParam = new QueryParam('foo', 'foo bar', true);
        $this->assertEquals('foo bar', $queryParam->getRawValue());
    }

    public function testClone()
    {
        $queryParam = new QueryParam('foo', 'foo bar');
        $queryParam = clone $queryParam;
        $this->assertFalse($queryParam->isRaw());
        $this->assertEquals('foo+bar', $queryParam->getValue());
        $queryParam = new QueryParam('foo', 'foo bar', true);
        $queryParam = clone $queryParam;
        $this->assertTrue($queryParam->isRaw());
        $this->assertEquals('foo bar', $queryParam->getValue());
    }

    public function testGetName()
    {
        $queryParam = new QueryParam('foo', 'foo bar');
        $this->assertEquals('foo', $queryParam->getName());
    }

    public function testGenerate()
    {
        $queryParam = new QueryParam('foo', 'foo bar');
        $this->assertEquals('foo=foo+bar', $queryParam->generate());

        $queryParamRaw = new QueryParam('foo', 'foo bar', true);
        $this->assertEquals('foo=foo bar', $queryParamRaw->generate());
    }

    public function testToString()
    {
        $queryParamRaw = new QueryParam('foo', 'foo bar', true);
        $this->assertEquals($queryParamRaw->generate(), (string)$queryParamRaw);
    }
}
