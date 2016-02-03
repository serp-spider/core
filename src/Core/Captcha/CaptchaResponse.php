<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Captcha;

interface CaptchaResponse
{

    /**
     * @return string the captcha image, base64 encoded
     */
    public function getImage();

    /**
     * @return array the data of the captcha. That's searchEngine dependant
     */
    public function getData();

    public function getIp();
}
