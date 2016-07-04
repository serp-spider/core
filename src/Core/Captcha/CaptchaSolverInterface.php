<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Captcha;

use Serps\Exception\CaptchaSolver\CaptchaNotSolvableException;
use Serps\Exception\CaptchaSolver\UnknownCaptchaTypeException;
use Serps\Exception\TimeoutException;

interface CaptchaSolverInterface
{
    /**
     * Solve a captcha and returns data depending on the captcha type
     * @param CaptchaResponse $captchaResponse
     * @param int $timeout the timeout for captcha resolution in milliseconds.
     * @param int $tryDelay delay between each try of getting the captcha data.
     * Leave it null to use adapter's default value
     * @return mixed
     * @throws CaptchaNotSolvableException
     * @throws UnknownCaptchaTypeException
     * @throws TimeoutException
     */
    public function solve(CaptchaResponse $captchaResponse, $timeout = null, $tryDelay = null);

    /**
     * Submit a captcha for resolution and returns a helper to get the output on an async way
     * @param CaptchaResponse $captchaResponse
     * @return CaptchaSolving
     * @throws CaptchaNotSolvableException
     * @throws UnknownCaptchaTypeException
     */
    public function solveAsync(CaptchaResponse $captchaResponse);
}
