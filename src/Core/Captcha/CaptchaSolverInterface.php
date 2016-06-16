<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Captcha;

use Serps\Exception\CaptchaSolver\CaptchaNotSolvableException;
use Serps\Exception\CaptchaSolver\UnknownCaptchaTypeException;

interface CaptchaSolverInterface
{
    /**
     * @param CaptchaResponse $captchaResponse
     * @return mixed
     * @throws CaptchaNotSolvableException
     * @throws UnknownCaptchaTypeException
     */
    public function solve(CaptchaResponse $captchaResponse);
}
