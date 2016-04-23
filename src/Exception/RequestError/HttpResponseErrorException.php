<?php
/**
 * @license see LICENSE
 */

namespace Serps\Exception\RequestError;

use Exception;

class HttpResponseErrorException extends RequestErrorException
{
    private $httpStatusCode;

    public function __construct($httpStatusCode, $message = '', $code = 0, Exception $previous = null)
    {
        $this->httpStatusCode = $httpStatusCode;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
}
