<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Captcha;

/**
 * this is an utility that helps to wait for a captcha to be solved
 */
interface AsyncCaptchaSolvingInterface
{
    public function getCaptcha();

    /**
     * try to get the captcha for the given time
     * @param int $time max time to wait in milli second
     * @param int $interval interval between 2 test in micro second
     * @return null|mixed null if captcha is not solved yet
     */
    public function tryFor($time, $interval = null);
}
