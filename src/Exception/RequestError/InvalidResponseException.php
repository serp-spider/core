<?php
/**
 * @license see LICENSE
 */

namespace Serps\Exception\RequestError;

use Exception;
use Serps\Core\Http\SearchEngineResponse;

class InvalidResponseException extends RequestErrorException
{
    private $searchEngineResponse;

    public function __construct(
        SearchEngineResponse $response,
        $message = '',
        $code = 0,
        Exception $previous = null
    ) {
    
        $this->searchEngineResponse = $response;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function getHttpStatusCode()
    {
        return $this->searchEngineResponse->getHttpResponseStatus();
    }

    /**
     * @return SearchEngineResponse
     */
    public function getResponse()
    {
        return $this->searchEngineResponse;
    }
}
