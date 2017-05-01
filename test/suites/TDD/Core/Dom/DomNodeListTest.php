<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Dom;

use PHPUnit\Framework\TestCase;
use Serps\Core\Dom\Css;
use Serps\Core\Dom\DocumentWrapper;
use Serps\Core\Dom\DomElement;
use Serps\Core\Dom\DomNodeList;
use Serps\Core\Dom\NullDomNode;

/**
 * @covers Serps\Core\Dom\DomNodeList
 * @covers Serps\Core\Dom\OtherDomNode
 * @covers Serps\Core\Dom\NullDomNode
 */
class DomNodeListTest extends TestCase
{

    public function testLength()
    {

        $dom = new DocumentWrapper('<html><div>foo</div><span class="foo bar">baz</span><span>qux</span></html>');

        $xpath = new \DOMXPath($dom->getDom());
        $elements = $xpath->query(Css::toXPath('span'));
        $nodeList = new DomNodeList($elements, $dom);


        $this->assertEquals(2, $nodeList->length);
    }

    public function testHasClass()
    {

        $dom = new DocumentWrapper('<html><div>foo</div><span class="foo bar">baz</span><span>qux</span><div class="baz"></div></html>');

        $xpath = new \DOMXPath($dom->getDom());
        $elements = $xpath->query(Css::toXPath('span'));
        $nodeList = new DomNodeList($elements, $dom);


        $this->assertTrue($nodeList->hasClass('foo'));
        $this->assertTrue($nodeList->hasClass('bar'));
        $this->assertFalse($nodeList->hasClass('baz'));


        $xpath = new \DOMXPath($dom->getDom());
        $elements = $xpath->query(Css::toXPath('div'));
        $nodeList = new DomNodeList($elements, $dom);


        $this->assertFalse($nodeList->hasClass('foo'));
        $this->assertFalse($nodeList->hasClass('bar'));
        $this->assertTrue($nodeList->hasClass('baz'));
    }


    public function testGetNodeAt()
    {

        $dom = new DocumentWrapper(
            '<html>
                <body>
                    <div>foo</div>
                    <span class="foo bar">baz</span>
                    <span>qux</span>
                    <div class="baz"></div>
                </body>
            </html>'
        );

        $nodes = $dom->cssQuery('.fake');

        $this->assertInstanceOf(NullDomNode::class, $nodes->getNodeAt(0));
        $this->assertInstanceOf(NullDomNode::class, $nodes->getNodeAt(200));


        $nodes = $dom->cssQuery('.foo');

        $this->assertInstanceOf(DomElement::class, $nodes->getNodeAt(0));
        $this->assertInstanceOf(NullDomNode::class, $nodes->getNodeAt(1));
    }
}
