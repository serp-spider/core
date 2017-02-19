<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Captcha;

class AsyncCaptchaSolvingCallback implements AsyncCaptchaSolvingInterface
{

    protected $getCaptchaCb;
    protected $captchaDone = null;
    protected $tryForDefaultDelay;

    /**
     * @param callable $getCaptcha handler called to get captcha if it  was solved
     * @param int $tryFprDefaultDelay default delay to use in the tryFor() method
     */
    public function __construct(callable $getCaptcha, $tryForDefaultDelay = 1000)
    {
        $this->getCaptchaCb = $getCaptcha;
        $this->tryForDefaultDelay = $tryForDefaultDelay;
    }

    public function getCaptcha()
    {
        if (null === $this->captchaDone) {
            $captcha = call_user_func($this->getCaptchaCb);
            if (false !== $captcha) {
                $this->captchaDone = $captcha;
            }
            return $captcha;
        } else {
            return $this->captchaDone;
        }
    }

    /**
     * try to get the captcha for the given time
     * @param int $time max time to wait in second
     * @param int $interval interval between 2 test in second
     * @return null|mixed
     */
    public function tryFor($time, $interval = null)
    {
        if (null == $interval) {
            $interval = $this->tryForDefaultDelay;
        }
        $tryUntil = microtime(true) + $time;
        while ($tryUntil > microtime(true)) {
            if ($c = $this->getCaptcha()) {
                return $c;
            }
            usleep($interval * 1000000);
        }
        return null;
    }
}
