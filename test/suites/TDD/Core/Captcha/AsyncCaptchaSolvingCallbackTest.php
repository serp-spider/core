<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\Core\Captcha;

use Serps\Core\Captcha\AsyncCaptchaSolvingCallback;

/**
 * @covers Serps\Core\Captcha\AsyncCaptchaSolvingCallback
 */
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
        // Use std class to fix hhvm no taking references for lambdas captured variables
        $count = new \stdClass();
        $count->count = 0;

        $asyncSolving = new AsyncCaptchaSolvingCallback(function () use ($count) {
            $count->count++;
            return $count->count > 2;
        });

        $this->assertEquals(0, $count->count);

        $this->assertEquals(false, $asyncSolving->tryFor(0.05, 0.06));
        $this->assertEquals(1, $count->count);
        $this->assertEquals(true, $asyncSolving->tryFor(10, 0.05));
        $this->assertEquals(3, $count->count);

        // Cached
        $this->assertEquals(true, $asyncSolving->tryFor(1, 0.5));
        $this->assertEquals(3, $count->count);
    }
}
