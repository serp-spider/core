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
}
