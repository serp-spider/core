<?php
/**
 * @license see LICENSE
 */

namespace Serps\Exception\CaptchaSolver;

use Exception;

/**
 * Thrown when a @see CaptchaSolverInterface does not support
 * the captcha type returned by @see CaptchaResponse::getCaptchaType
 */
class UnknownCaptchaTypeException extends CaptchaNotSolvableException
{

    protected $captchaType;

    public function __construct($cpatchaType, $message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string the captcha type that is unknown
     */
    public function getCaptchaType()
    {
        return $this->captchaType;
    }



}
