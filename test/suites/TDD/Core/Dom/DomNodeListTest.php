<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Dom;

use PHPUnit\Framework\TestCase;
use Serps\Core\Dom\Css;
use Serps\Core\Dom\DocumentWrapper;
use Serps\Core\Dom\DomNodeList;

/**
 * @covers Serps\Core\Dom\DomNodeList
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
}
