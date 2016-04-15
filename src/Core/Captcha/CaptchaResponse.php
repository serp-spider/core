<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Captcha;

/**
 * Captcha response:
 *
 * A captcha response contains the captcha data that can be solved by a captcha solver.
 *
 * The data type vary. The reason is that captcha are not necessary an image. For instance it can also be some
 * recaptcha v2.
 *
 * To solve this problem and to allow everything to fit in one interface, the captcha response contains the data type
 * in addition of the data itself, this way the interface is able to handle any captcha type.
 *
 * The problem is that all captcha service wont be able to solve every captcha type. That should be considered
 * when developing on the top of the library.
 */
interface CaptchaResponse
{

    const CAPTCHA_TYPE_IMAGE = 'image';
    const CAPTCHA_TYPE_RECAPTCHAV2 = 'recaptcha_v2';

    /**
     * Gets the captcha type, it defines what kind of data returns the method CaptchaResponse::getData
     * @return string the captcha type
     */
    public function getCaptchaType();

    /**
     * Get the data of the captcha. The returned data depends on the value of @see CaptchaResponse::getCaptchaType.
     *
     * Here are the known captcha types:
     *
     * - CAPTCHA_TYPE_IMAGE: returns a base64 encoded image
     * - CAPTCHA_TYPE_RECAPTCHAV2: not defined yet
     *
     * @return mixed the captcha data.
     */
    public function getData();
}
