<?php
/**
 * @license see LICENSE
 */

namespace Serps\Exception;

use Exception;

class HttpResponseErrorException extends RequestErrorException
{
    private $httpStatusCode;

    public function __construct($httpStatusCode, $message, $code, Exception $previous)
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
