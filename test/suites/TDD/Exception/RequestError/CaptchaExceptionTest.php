<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\TDD\Exception\RequestError;

use Serps\Core\Captcha\CaptchaResponse;
use Serps\Exception\RequestError\CaptchaException;

/**
 * @covers Serps\Exception\RequestError\CaptchaException
 */
class CaptchaExceptionTest extends \PHPUnit_Framework_TestCase
{

    public function testCaptchaException()
    {
        $captchaResponse = $this->getMock(CaptchaResponse::class);
        $captchaException = new CaptchaException($captchaResponse);

        $this->assertSame($captchaResponse, $captchaException->getCaptcha());
    }
}
