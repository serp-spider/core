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

    public function testGenerateAndToString()
    {
        $queryParam = new QueryParam('foo', 'foo bar');
        $this->assertEquals('foo=foo+bar', $queryParam->generate());
        $this->assertEquals($queryParam->generate(), (string)$queryParam);

        $queryParamRaw = new QueryParam('foo', 'foo bar', true);
        $this->assertEquals('foo=foo bar', $queryParamRaw->generate());
        $this->assertEquals($queryParamRaw->generate(), (string)$queryParamRaw);
    }


    /**
     * https://github.com/serp-spider/core/pull/25
     */
    public function testToStringWithNullValueAndNumericName()
    {
        $queryParamRaw = new QueryParam(14, null, true);
        $this->assertEquals('14', (string) $queryParamRaw);
    }

    public function testArrayValue()
    {
        $queryParam = new QueryParam('foo', ['foo', 'foo bar', 'foo' => 'bar', 'qux' => ['foobar', 'quxbar']]);
        $this->assertEquals(['foo', 'foo bar', 'foo' => 'bar', 'qux' => ['foobar', 'quxbar']], $queryParam->getValue());
        $this->assertEquals('foo[0]=foo&foo[1]=foo+bar&foo[foo]=bar&foo[qux][0]=foobar&foo[qux][1]=quxbar', $queryParam->generate());
    }
}
