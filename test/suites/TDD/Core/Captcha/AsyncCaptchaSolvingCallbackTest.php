<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Captcha;

use Serps\Core\Captcha\AsyncCaptchaSolvingCallback;

class AsyncCaptchaSolvingCallbackTest extends \PHPUnit_Framework_TestCase
{

    public function testCaptchaSolvingCallback()
    {
        $count = 0;

        $asyncSolving = new AsyncCaptchaSolvingCallback(function () use (&$count) {
            $count++;
            return $count > 2;
        });

        $this->assertEquals(0, $count);

        $this->assertEquals(false, $asyncSolving->getCaptcha());
        $this->assertEquals(1, $count);
        $this->assertEquals(false, $asyncSolving->getCaptcha());
        $this->assertEquals(2, $count);
        $this->assertEquals(true, $asyncSolving->getCaptcha());
        $this->assertEquals(3, $count);

        // Cached
        $this->assertEquals(true, $asyncSolving->getCaptcha());
        $this->assertEquals(3, $count);
    }

    public function testCaptchaSolvingCallbackTryFor()
    {
        $count = 0;

        $asyncSolving = new AsyncCaptchaSolvingCallback(function () use (&$count) {
            $count++;
            return $count > 2;
        });

        $this->assertEquals(0, $count);

        $this->assertEquals(false, $asyncSolving->tryFor(1, 1000));
        $this->assertEquals(1, $count);
        $this->assertEquals(true, $asyncSolving->tryFor(10, 50));
        $this->assertEquals(3, $count);

        // Cached
        $this->assertEquals(true, $asyncSolving->tryFor(1, 1000));
        $this->assertEquals(3, $count);
    }
}
