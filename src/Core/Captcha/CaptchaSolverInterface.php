<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Captcha;

interface CaptchaSolverInterface
{

    public static function solve(CaptchaResponse $captchaResponse);
}
