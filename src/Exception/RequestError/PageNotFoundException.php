<?php
/**
 * @license see LICENSE
 */

namespace Serps\Exception\RequestError;

use Exception;

class PageNotFoundException extends HttpResponseErrorException
{
    public function __construct($message, Exception $code, Exception $previous)
    {
        parent::__construct(404, $message, $code, $previous);
    }
}
