<?php
/**
 * @license see LICENSE
 */

namespace Serps\Exception\RequestError;

use Exception;

class PageNotFoundException extends HttpResponseErrorException
{
    public function __construct($message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct(404, $message, $code, $previous);
    }
}
