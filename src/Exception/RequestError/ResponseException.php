<?php
/**
 * @license see LICENSE
 */

namespace Serps\Exception\RequestError;

use Serps\Core\Http\SearchEngineResponse;

class ResponseException extends RequestErrorException
{

    /**
     * @var SearchEngineResponse
     */
    protected $response;

    public function __construct(SearchEngineResponse $response)
    {
        $this->response = $response;
    }

    /**
     * @return SearchEngineResponse
     */
    public function getResponse()
    {
        return $this->response;
    }
}
