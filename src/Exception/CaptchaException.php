<?php
/**
 * @license see LICENSE
 */

namespace Serps\Exception;

use Serps\Core\Captcha\CaptchaResponse;

class CaptchaException extends RequestErrorException
{

    /**
     * @var CaptchaResponse
     */
    protected $captcha;

    public function __construct(CaptchaResponse $captchaResponse)
    {
        $this->captcha = $captchaResponse;
    }

    /**
     * @return CaptchaResponse
     */
    public function getCaptcha()
    {
        return $this->captcha;
    }
}
