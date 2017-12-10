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
 * @covers \Serps\Core\Dom\DomElement
 */
class DomElementTest extends TestCase
{

    public function testHasClass()
    {

        $dom = new DocumentWrapper('<html><div>foo</div><span class="foo bar">baz</span><span>qux</span></html>');

        $xpath = new \DOMXPath($dom->getDom());
        $elements = $xpath->query(Css::toXPath('span'));
        $nodeList = new DomNodeList($elements, $dom);

        $foobar = $nodeList->item(0);

        $this->assertInstanceOf(DomElement::class, $foobar);
        $this->assertTrue($foobar->hasClass('foo'));
        $this->assertTrue($foobar->hasClass('bar'));
        $this->assertFalse($foobar->hasClass('vaz'));

        $noClass = $nodeList->item(1);
        $this->assertFalse($noClass->hasClass('foo'));
    }

    public function testTagName()
    {
        $dom = new DocumentWrapper('<html><div>foo</div><span class="foo bar">baz</span><span>qux</span></html>');

        $xpath = new \DOMXPath($dom->getDom());
        $elements = $xpath->query(Css::toXPath('span'));
        $nodeList = new DomNodeList($elements, $dom);

        $foobar = $nodeList->item(0);

        $this->assertEquals('span', $foobar->getTagName());
    }

    public function testGetNodeValue()
    {
        $dom = new DocumentWrapper('<html><div>foo</div><span class="foo bar">baz</span><span>qux</span></html>');

        $xpath = new \DOMXPath($dom->getDom());
        $elements = $xpath->query(Css::toXPath('span'));
        $nodeList = new DomNodeList($elements, $dom);

        $foobar = $nodeList->item(0);

        $this->assertEquals('baz', $foobar->getNodeValue());
    }

    public function testHasClasses()
    {

        $dom = new DocumentWrapper('<html><div>foo</div><span class="foo bar">baz</span><span>qux</span></html>');

        $xpath = new \DOMXPath($dom->getDom());
        $elements = $xpath->query(Css::toXPath('span'));
        $nodeList = new DomNodeList($elements, $dom);

        $foobar = $nodeList->item(0);

        $this->assertInstanceOf(DomElement::class, $foobar);
        $this->assertTrue($foobar->hasClasses(['foo']));
        $this->assertTrue($foobar->hasClasses(['foo', 'bar']));
        $this->assertFalse($foobar->hasClasses(['foo', 'baz']));
        $this->assertFalse($foobar->hasClasses(['baz']));

        $noClass = $nodeList->item(1);
        $this->assertFalse($noClass->hasClasses(['foo']));
    }
}
