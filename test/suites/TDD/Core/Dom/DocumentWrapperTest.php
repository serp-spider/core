<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Dom;

use Serps\Core\Dom\DocumentWrapper;
use Serps\Core\Dom\DomNodeList;

/**
 * @covers Serps\Core\Dom\DocumentWrapper
 * @covers Serps\Core\Dom\DomNodeList
 * @covers Serps\Core\Dom\DomXpath
 */
class DocumentWrapperTest extends \PHPUnit_Framework_TestCase
{

    public function testDomNodeList()
    {
        $domString =
            '<foo>
                <bar a="b"></bar>
                <bar></bar>
                <!-- comment -->
            </foo>';

        $document = new DocumentWrapper($domString);
        $list = $document->cssQuery('bar');

        $this->assertInstanceOf(DomNodeList::class, $list);
        $this->assertEquals(2, $list->length);
        $this->assertCount(2, $list);
    }
}
