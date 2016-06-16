<?php
/**
 * @license see LICENSE
 */

namespace Serps\Exception\CaptchaSolver;

use Exception;
use Serps\Core\Captcha\CaptchaSolverInterface;

/**
 * Thrown when a @see CaptchaSolverInterface does not support
 * the captcha type returned by @see CaptchaResponse::getCaptchaType
 */
class UnknownCaptchaTypeException extends CaptchaNotSolvableException
{

    protected $captchaType;

    public function __construct($captchaType, CaptchaSolverInterface $captchaSolver, $additionalMessage = null, $code = 0, Exception $previous = null)
    {
        $this->captchaType = $captchaType;

        $captchaSolverClass = get_class($captchaSolver);
        $message =  "Captcha of type $captchaType is not solvable by $captchaSolverClass.";
        if($additionalMessage){
            $message .= ' ' . $additionalMessage;
        }

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
