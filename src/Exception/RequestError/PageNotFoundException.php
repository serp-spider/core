<?php
/**
 * @license see LICENSE
 */

namespace Serps\Exception\RequestError;

use Exception;
use Serps\Core\Http\SearchEngineResponse;

class PageNotFoundException extends InvalidResponseException
{
    public function __construct(SearchEngineResponse $response, $message = '', $code = 0, Exception $previous = null)
    {
        parent::__construct($response, $message, $code, $previous);
    }
}
