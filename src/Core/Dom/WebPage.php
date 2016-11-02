<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Dom;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;
use Serps\Core\Psr7\RequestBuilder;
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
     * Get data from a given html form. Form data can be fill with given data
     *
     * @param \DOMElement $formNode the form DOMElement
     * @param array $formData optional data to replace the default data from the html
     * @param bool $strict by default the data returned will we be processed from form element inputs and
     * given data that are not present as an input in the form will be ignored. Pass this argument to false in order
     * to return all data from the original data
     * @param bool $submit by default this method will search for the first submit and get data from it (if the
     * submit is named)
     * @return array
     */
    public function formGetData(\DOMElement $formNode, array $formData = [], $strict = true, $submit = true)
    {

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

        if (!$strict) {
            foreach ($formData as $queryName => $queryValue) {
                if (!in_array($queryName, $consumed)) {
                    $queryItems[] = http_build_query([$queryName => $queryValue]);
                }
            }
        }

        if (true === $submit) {
            // when the submit button is pressed, and if the submit has a name, it will add an item in the query string
            $items = $this->cssQuery('input[type="submit"], button', $formNode);

            foreach ($items as $item) {
                if ($item->hasAttribute('disabled')) {
                    continue;
                }
                $name = $item->hasAttribute('name') ? $item->getAttribute('name') : false;

                if ($item->tagName == 'input') {
                    if (!$name) {
                        break;
                    }
                    $value = $item->hasAttribute('value')
                        ? $item->getAttribute('value')
                        : 'Submit'; // chrome uses "Submit" as default value
                    $queryItems[] = http_build_query([$name => $value]);
                    break;
                } else {
                    if ($item->hasAttribute('type')
                        && in_array($item->getAttribute('type'), ['button', 'reset'])
                    ) {
                    // buttons with type = 'reset' or 'button' are not valid to submit the form
                        continue;
                    } else {
                        if (!$name) {
                            break;
                        }
                        $value = $item->hasAttribute('value')
                            ? $item->getAttribute('value')
                            : '';
                        $queryItems[] = http_build_query([$name => $value]);
                        break;
                    }
                }
            }
        }

        return $queryItems;
    }

    /**
     * Build a request from the given form. The form data, the form method and action will be considered
     * @param \DOMElement $formNode
     * @param array $formData @see formGetData
     * @param bool $strict @see formGetData
     * @param bool $submit @see formGetData
     * @param RequestInterface|null $request an optional request instance to fill with the form preset.
     * If this parameter is omitted the method will try to find a request builder
     * from zendframework/zend-diactoros or guzzlehttp/psr7
     * @return RequestInterface
     */
    public function requestFromForm(
        \DOMElement $formNode,
        array $formData = [],
        $strict = true,
        $submit = true,
        RequestInterface $request = null
    ) {


        if (null == $request) {
            $request = RequestBuilder::buildRequest();
        }



        $formAction = $formNode->getAttribute('action');
        if (!$formAction) {
            $formAction = '';
        }
        $formUrl = $this->getUrl()->resolve($formAction);

        $method = $formNode->getAttribute('method');
        if (!$method) {
            $method = 'get';
        } else {
            $method = strtolower($method);
        }

        $queryItems = $this->formGetData($formNode, $formData, $strict, $submit);

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
                // Submit are not parsed they are processed after because only 1 submit will be used
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
