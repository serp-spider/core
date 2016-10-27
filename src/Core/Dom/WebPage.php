<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Serps\Core\Url\UrlArchiveInterface;

class WebPage extends DocumentWrapper
{


    /**
     * @var UrlArchiveInterface
     */
    protected $url;

    public function __construct($domString, UrlArchiveInterface $url, $defaultEncoding = null)
    {
        parent::__construct($domString, $defaultEncoding);
        $this->url = $url;
    }

    /**
     * @return UrlArchiveInterface
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param \DOMElement $formNode
     * @param array $formData
     * @param RequestInterface|null $request
     * @return RequestInterface
     */
    public function requestFromForm(\DOMElement $formNode, array $formData = [], RequestInterface $request = null)
    {

        if (null == $request) {
            if (class_exists('Zend\Diactoros\Request')) {
                $request = new \Zend\Diactoros\Request();
            } elseif (class_exists('GuzzleHttp\Psr7\Request')) {
                $request = new \GuzzleHttp\Psr7\Request();
            } else {
                throw new \InvalidArgumentException(
                    'No request to fill for the form. '
                    . 'Please provide a RequestInterface instance or make one of these package available: '
                    . '[zendframework/zend-diactoros, guzzlehttp/psr7]'
                );
            }
        }



        $formAction = $formNode->getAttribute('action');
        if (!$formAction) {
            $formAction = '';
        }
        $formUrl = $this->getUrl()->resolve($formAction);

        $method = $formNode->getAttribute('method');
        if (!$method) {
            $method = 'get';
        }

        $items = $this->xpathQuery('(//input | //textarea | //select)', $formNode);

        $consumed = [];

        $queryItems = [];


        foreach ($items as $item) {
            /* @var \DOMElement $item */

            if ($item->hasAttribute('disabled')) {
                continue;
            }
            switch ($item->tagName) {
                case 'input':
                    $query = $this->parseInput($item, $formData, $consumed);
                    break;
                case 'textarea':
                    $query = $this->parseTextArea($item, $formData, $consumed);
                    break;
                case 'select':
                    $query = $this->parseSelect($item, $formData, $consumed);
                    break;
                default:
                    $query = null;
                    break;
            }

            if ($query) {
                $queryItems[] = $query;
            }
        }

        foreach ($formData as $queryName => $queryValue) {
            if (!in_array($queryName, $consumed)) {
                $queryItems[] = http_build_query([$queryName => $queryValue]);
            }
        }


        $url = $request->getUri()
            ->withScheme($this->nullToEmpty($formUrl->getScheme()))
            ->withUserInfo($this->nullToEmpty($formUrl->getUser()), $this->nullToEmpty($formUrl->getPass()))
            ->withHost($this->nullToEmpty($formUrl->getHost()))
            ->withPort($formUrl->getPort())
            ->withPath($this->nullToEmpty($formUrl->getPath()))
            ->withFragment($this->nullToEmpty($formUrl->getHash()));

        if (!in_array(strtolower($method), ['post', 'put'])) {
            $url = $url->withQuery(implode('&', $queryItems));
        } else {
            $body = $request->getBody();
            $body->rewind();
            $body->write(implode('&', $queryItems));
            $request = $request->withBody($body);
        }

        // TODO enctype
        return $request->withUri($url)->withMethod($method);
    }

    private function nullToEmpty($data)
    {
        return $data === null ? '' : $data;
    }

    private function parseInput(\DOMElement $input, array $formData, array &$consumed)
    {
        $name = strtolower($input->getAttribute('name'));

        if (!$name) {
            return false;
        }

        if (isset($formData[$name])) {
            $consumed[] = $name;
            return http_build_query([$name => $formData[$name]]);
        }

        $inputType = strtolower($input->getAttribute('type'));
        switch ($inputType) {
            case 'file':
                // TODO ?
                break;
            case 'submit':
                // TODO
                break;
            case 'radio':
            case 'checkbox':
                if ($input->hasAttribute('checked')) {
                    return http_build_query([$name => $input->getAttribute('value')]);
                }
                break;
            default:
                if ($input->hasAttribute('value')) {
                    return http_build_query([$name => $input->getAttribute('value')]);
                } else {
                    return urlencode($name);
                }
                break;
        }
    }

    private function parseSelect(\DOMElement $select, array $formData, array &$consumed)
    {

        $name = strtolower($select->getAttribute('name'));

        if (isset($formData[$name])) {
            $consumed[] = $name;
            return http_build_query([$name => $formData[$name]]);
        }

        $isMultiple = $select->hasAttribute('multiple');
        if ($isMultiple) {
            $values = [];
        }


        $options = $this->cssQuery('option', $select);
        foreach ($options as $option) {
            /* @var \DOMElement $option */
            if ($option->hasAttribute('disabled')) {
                continue;
            }
            if ($option->hasAttribute('selected')) {
                $optionValue = $option->hasAttribute('value') ? $option->getAttribute('value') : $option->nodeValue;
                if ($isMultiple) {
                    $values[] = http_build_query([$name => $optionValue]);
                } else {
                    return http_build_query([$name => $optionValue]);
                }
            }
        }


        if ($isMultiple) {
            return implode('&', $values);
        } else {
            return urlencode($name);
        }
    }

    private function parseTextArea(\DOMElement $textarea, array $formData, array &$consumed)
    {
        $name = strtolower($textarea->getAttribute('name'));

        if (isset($formData[$name])) {
            $consumed[] = $consumed;
            return http_build_query([$name => $formData[$name]]);
        }

        return http_build_query([$name => $textarea->nodeValue]);
    }
}
