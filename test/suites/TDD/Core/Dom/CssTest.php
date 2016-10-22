<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Dom;

use Serps\Core\Dom\Css;
use Symfony\Component\CssSelector\CssSelector;
use Symfony\Component\CssSelector\CssSelectorConverter;

/**
 * @covers Serps\Core\Dom\Css
 */
class CssTest extends \PHPUnit_Framework_TestCase
{

    public function testToXpath()
    {

        if (class_exists('Symfony\Component\CssSelector\CssSelectorConverter')) {
            // Version >= 2.8
            $converter = new CssSelectorConverter();
        } else {
            // Version < 2.8
            $converter = new CssSelector();
        }

        $expression = 'div.a span>#a input[type="text"]';

        $this->assertEquals($converter->toXPath($expression), Css::toXPath($expression));
    }
}
