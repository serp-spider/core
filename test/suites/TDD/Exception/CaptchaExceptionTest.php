<?php
/**
 * @license see LICENSE
 */

namespace Serps\Test\TDD\Exception;

use Serps\Core\Captcha\CaptchaResponse;
use Serps\Exception\CaptchaException;

/**
 * @covers Serps\Exception\CaptchaException
 */
class CaptchaExceptionTest extends \PHPUnit_Framework_TestCase
{

    public function testCaptchaException(){
        $captchaResponse = $this->getMock(CaptchaResponse::class);
        $captchaException = new CaptchaException($captchaResponse);

        $this->assertSame($captchaResponse, $captchaException->getCaptcha());
    }

}
