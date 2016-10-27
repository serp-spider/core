<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Dom;

use Serps\Core\Dom\WebPage;
use Serps\Core\Url;
use Zend\Diactoros\Request;

/**
 * @covers Serps\Core\Dom\WebPage
 */
class WebPageTest extends \PHPUnit_Framework_TestCase
{

    public function testParseForm()
    {
        $dom = <<<EOF
        <html>
            <body>
                <form>
                    <input type='text' name='foo' value='bar' />
                    <input type='text' name='bar' />
                    <input type='text' value="baz"/>
                    <input type='text' name='qux' value='nothing' disabled />
                </form>
            </body>
        </html>
EOF;

        $webpage = new WebPage($dom, Url::fromString('http://example.com'));
        $form = $webpage->cssQuery('form')->item(0);

        $request = $webpage->requestFromForm($form);

        $this->assertEquals('http://example.com?foo=bar&bar', (string) $request->getUri());
        $this->assertEquals('get', (string) $request->getMethod());
    }

    public function testFormPost()
    {
        $dom = <<<EOF
        <html>
            <body>
                <form method="POST">
                    <input type='text' name='foo' value='bar' />
                </form>
            </body>
        </html>
EOF;

        $webpage = new WebPage($dom, Url::fromString('http://example.com'));
        $form = $webpage->cssQuery('form')->item(0);

        $request = $webpage->requestFromForm($form);

        $this->assertEquals('http://example.com', (string) $request->getUri());
        $this->assertEquals('foo=bar', (string) $request->getBody());
        $this->assertEquals('post', (string) $request->getMethod());
    }

    public function testParseSelect()
    {
        $dom = <<<EOF
        <html>
            <body>
                <form action="/formAction">
                    <select name="foo">
                        <option value="1">first</option>
                        <option value="2" selected>second</option>
                        <option value="3" selected>third</option>
                    </select>

                    <select name="bar" disabled>
                        <option value="1">first</option>
                        <option value="2" selected>second</option>
                        <option value="3" selected>third</option>
                    </select>

                    <select name="baz">
                        <option value="1">first</option>
                        <option value="2" disabled selected>second</option>
                        <option value="3" selected>third</option>
                    </select>

                    <select name="qux" multiple>
                        <option value="1">first</option>
                        <option value="2" disabled selected>second</option>
                        <option value="3" selected>third</option>
                        <option value="4" selected>fourth</option>
                        <option value="5">fifth</option>
                        <option value="6" selected>sixth</option>
                        <option value="2" selected>second</option>
                    </select>
                    <submit></submit>
                </form>
            </body>
        </html>
EOF;

        $webpage = new WebPage($dom, Url::fromString('http://example.com'));
        $form = $webpage->cssQuery('form')->item(0);

        $request = $webpage->requestFromForm($form);

        $this->assertEquals('http://example.com/formAction?foo=2&baz=3&qux=3&qux=4&qux=6&qux=2', (string) $request->getUri());
    }

    public function testWithData()
    {
        $dom = <<<EOF
        <html>
            <body>
                <form action="/formAction">
                    <select name="foo">
                        <option value="1">first</option>
                        <option value="2" selected>second</option>
                        <option value="3" selected>third</option>
                    </select>
                    <input type='text' name='bar' />
                    <submit/>
                </form>
            </body>
        </html>
EOF;

        $webpage = new WebPage($dom, Url::fromString('http://example.com'));
        $form = $webpage->cssQuery('form')->item(0);

        $request = $webpage->requestFromForm($form, ['foo' => 'homer', 'baz' => 'simpson']);
        $this->assertEquals('http://example.com/formAction?foo=homer&bar', (string) $request->getUri());
        // Same but non strict
        $request = $webpage->requestFromForm($form, ['foo' => 'homer', 'baz' => 'simpson'], false);
        $this->assertEquals('http://example.com/formAction?foo=homer&bar&baz=simpson', (string) $request->getUri());
    }

    public function testNamedSubmitWithValue()
    {
        $dom = <<<EOF
        <html>
            <body>
                <form action="/formAction">
                    <input type='text' name='bar' />
                    <input type="submit" name="foo" value="baz" />
                    <input type="submit" name="bar" value="qux" />
                </form>
            </body>
        </html>
EOF;

        $webpage = new WebPage($dom, Url::fromString('http://example.com'));
        $form = $webpage->cssQuery('form')->item(0);

        $request = $webpage->requestFromForm($form);
        $this->assertEquals('http://example.com/formAction?bar&foo=baz', (string) $request->getUri());
    }

    public function testNamedSubmitWithoutValue()
    {
        $dom = <<<EOF
        <html>
            <body>
                <form action="/formAction">
                    <input type='text' name='bar' />
                    <input type="submit" name="foo"/>
                    <input type="submit" name="bar" value="qux" />
                </form>
            </body>
        </html>
EOF;

        $webpage = new WebPage($dom, Url::fromString('http://example.com'));
        $form = $webpage->cssQuery('form')->item(0);

        $request = $webpage->requestFromForm($form);
        $this->assertEquals('http://example.com/formAction?bar&foo=Submit', (string) $request->getUri());
    }

    public function testAnonymousSubmit()
    {
        $dom = <<<EOF
        <html>
            <body>
                <form action="/formAction">
                    <input type='text' name='bar' />
                    <input type="submit"/>
                    <input type="submit" name="bar" value="qux" />
                </form>
            </body>
        </html>
EOF;

        $webpage = new WebPage($dom, Url::fromString('http://example.com'));
        $form = $webpage->cssQuery('form')->item(0);

        $request = $webpage->requestFromForm($form);
        $this->assertEquals('http://example.com/formAction?bar', (string) $request->getUri());
    }

    public function testDisabledSubmit()
    {
        $dom = <<<EOF
        <html>
            <body>
                <form action="/formAction">
                    <input type='text' name='bar' />
                    <input type="submit" disabled/>
                    <input type="submit" name="bar" value="qux" />
                </form>
            </body>
        </html>
EOF;

        $webpage = new WebPage($dom, Url::fromString('http://example.com'));
        $form = $webpage->cssQuery('form')->item(0);

        $request = $webpage->requestFromForm($form);
        $this->assertEquals('http://example.com/formAction?bar&bar=qux', (string) $request->getUri());
    }

    public function testNamedButtonSubmitWithValue()
    {
        $dom = <<<EOF
        <html>
            <body>
                <form action="/formAction">
                    <button type='submit' name='foobutton' value='foobar'>click here</button>
                    <input type='text' name='bar' />
                    <input type="submit" disabled/>
                    <input type="submit" name="bar" value="qux" />
                </form>
            </body>
        </html>
EOF;

        $webpage = new WebPage($dom, Url::fromString('http://example.com'));
        $form = $webpage->cssQuery('form')->item(0);

        $request = $webpage->requestFromForm($form);
        $this->assertEquals('http://example.com/formAction?bar&foobutton=foobar', (string) $request->getUri());
    }

    public function testNamedButtonSubmitWithoutValue()
    {
        $dom = <<<EOF
        <html>
            <body>
                <form action="/formAction">
                    <button type='submit' name='foobutton'>click here</button>
                    <input type='text' name='bar' />
                    <input type="submit" disabled/>
                    <input type="submit" name="bar" value="qux" />
                </form>
            </body>
        </html>
EOF;

        $webpage = new WebPage($dom, Url::fromString('http://example.com'));
        $form = $webpage->cssQuery('form')->item(0);

        $request = $webpage->requestFromForm($form);
        $this->assertEquals('http://example.com/formAction?bar&foobutton=', (string) $request->getUri());
    }
}
