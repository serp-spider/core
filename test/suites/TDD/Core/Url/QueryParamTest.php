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
