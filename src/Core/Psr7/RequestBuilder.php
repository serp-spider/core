<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Psr7;

use Psr\Http\Message\RequestInterface;
use Serps\Exception;
use Zend\Diactoros\Request as ZendDiactorosRequest;
use GuzzleHttp\Psr7\Request as GuzzlePsr7Request;
use Zend\Diactoros\Stream as ZendDiactorosStream;

class RequestBuilder
{


    /**
     * @param $url
     * @param $method
     * @param $headers
     * @param $body
     * @return RequestInterface
     * @throws Exception
     */
    public static function buildRequest($url = null, $method = null, $headers = null, $body = null)
    {
        if (class_exists(ZendDiactorosRequest::class)) {
            $request = self::requestFromZendDiactoros($url, $method, $headers, $body);
        } elseif (class_exists(GuzzlePsr7Request::class)) {
            $request = self::requestFromGuzzlePsr7($url, $method, $headers, $body);
        } else {
            throw new Exception(
                'No PSR-7 implementation was found. '
                . 'Please make one of these package available: '
                . '"zendframework/zend-diactoros" or "guzzlehttp/psr7"'
            );
        }

        return $request;
    }

    protected static function requestFromZendDiactoros($url = null, $method = null, $headers = null, $body = null)
    {
        if (is_string($body)) {
            $bodyStr = $body;
            $body = new ZendDiactorosStream('php://temp', 'r+');
            $body->write($bodyStr);
        }

        return new ZendDiactorosRequest(
            $url ? (string)  $url : '',
            $method ? $method : 'GET',
            $body ? $body : 'php://temp',
            $headers ? $headers : []
        );
    }

    protected static function requestFromGuzzlePsr7($url = null, $method = null, array $headers = null, $body = null)
    {
        return new GuzzlePsr7Request(
            $method ? $method : 'GET',
            $url ? (string)  $url : '',
            $headers ? $headers : [],
            $body ? $body : ''
        );
    }
}
