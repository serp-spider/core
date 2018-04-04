<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Dom;

use Serps\Core\Dom\DocumentWrapper;
use Serps\Core\Dom\EmptyDomNodeList;
use Serps\Core\Dom\NullDomNode;

/**
 * @covers \Serps\Core\Dom\DomXpath
 */
class DomXpathTest extends \PHPUnit_Framework_TestCase
{

    public function testNullDomNodeContext()
    {
        $domString =
            '<foo>
                <bar a="b"></bar>
                <baz a="b"></baz>
            </foo>';


        // if xpath is absolute
        $document = new DocumentWrapper($domString);
        $fooBar = $document->getXpath()->query('//foo/bar', new NullDomNode());
        $this->assertEquals(1, $fooBar->length);

        // if xpath is relative
        $document = new DocumentWrapper($domString);
        $fooBar = $document->getXpath()->query('descendant-or-self::bar', new NullDomNode());
        $this->assertInstanceOf(EmptyDomNodeList::class, $fooBar);
    }
}
