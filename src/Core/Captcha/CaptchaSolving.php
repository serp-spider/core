<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Captcha;

/**
 * this is an utility that helps to wait for a captcha to be solved
 */
class CaptchaSolving
{

    protected $getCaptha;
    protected $captchaDone = null;

    public function __construct(callable $getCaptcha)
    {
        $this->getCaptha = $getCaptcha;
    }

    public function getCaptcha()
    {
        if(null === $this->captchaDone){
            $captcha = call_user_func($this->getCaptha);
            if(false !== $captcha){
                $this->captchaDone = $captcha;
            }
            return $captcha;
        }else{
            return $this->captchaDone;
        }
    }

    /**
     * try to get the captcha for the given time
     * @param int $time max time to wait in second
     * @param int $interval interval between 2 test in micro second
     * @return null|mixed
     */
    public function tryFor($time, $interval = 200)
    {
        $tryUntil = time() + $time;
        while ($tryUntil > time()) {
            if ($c = $this->getCaptcha()) {
                return $c;
            }
            usleep($interval);
        }
        return null;
    }
}
