<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Url;

use Serps\Core\Url\UrlArchiveInterface;
use Serps\Core\Url\QueryParam;

/**
 * This trait offers implementation for @see UrlArchiveInterface
 */
trait UrlArchiveTrait
{

    /**
     * @var QueryParam[]
     */
    protected $query = [];
    protected $hash;
    protected $path;
    protected $scheme;

    /**
     * host name e.g: ``www.example.com``
     */
    protected $host;

    /**
     * @param string $host
     * @param QueryParam[] $query
     * @param $hash
     * @param $path
     * @param string $scheme
     */
    public function __construct(
        $host,
        $path = '',
        $scheme = 'https',
        array $query = [],
        $hash = ''
    ) {


        $this->query = $query;
        $this->hash = $hash;
        $this->path = $path;
        $this->scheme = $scheme;
        $this->host = $host;
    }

    /**
     * @return QueryParam[]
     */
    public function getParams()
    {
        return $this->query;
    }

    /**
     * Builds an url instance from an url string
     * @param string $url the url to parse
     * @return static
     */
    public static function fromString($url)
    {
        $urlItems = parse_url($url);

        $query = [];
        if (isset($urlItems['query'])) {
            // Don't use a parse_str.
            // Because it returns an empty string in the value, when it should be null.
            // For example: param1&param2=
            // The value of param1 must be a null.
            $queryItems = explode('&', $urlItems['query']);
            foreach ($queryItems as $queryItem) {
                list($param, $value) = explode('=', $queryItem, 2);
                $query[$param] = new QueryParam($param, $value);
            }
        }

        return new static(
            isset($urlItems['host']) ? $urlItems['host'] : null,
            isset($urlItems['path']) ? $urlItems['path'] : null,
            isset($urlItems['scheme']) ? $urlItems['scheme'] : null,
            $query,
            isset($urlItems['fragment']) ? $urlItems['fragment'] : null
        );
    }


    /**
     * Set the scheme.
     * ``foo`` in ``http://www.example.com#foo``
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set the scheme.
     * ``some/path`` in ``http://www.example.com/some/path``
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get the scheme.
     * ``http`` in ``http://www.example.com``
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * Get the hostname.
     * ``www.example.com`` in ``http://www.example.com``
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getParamValue($name, $default = null)
    {
        if (isset($this->query[$name])) {
            return $this->query[$name]->getValue();
        }
        return $default;
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getParamRawValue($name, $default = null)
    {
        if (isset($this->query[$name])) {
            return $this->query[$name]->getRawValue();
        }
        return $default;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasParam($name)
    {
        return isset($this->query[$name]);
    }

    /**
     * Get the full uri: ``http://www.example.com/path?param=value#hash``
     * @return string
     */
    public function buildUrl()
    {
        $uri = $this->getScheme() . '://' . $this->getHost();

        if ($path = $this->getPath()) {
            $uri .= '/' . ltrim($path, '/');
        }

        if ($query = $this->getQueryString()) {
            $uri .= '?' . $query;
        }

        if ($hash = $this->getHash()) {
            $uri .= '#' . $this->getHash();
        }

        return $uri;
    }

    public function __toString()
    {
        return $this->buildUrl();
    }

    /**
     * Get the query string.
     * ``foo=bar&bar=foo`` in ``http://www.example.com?foo=bar&bar=foo``
     * @return string
     */
    public function getQueryString()
    {
        return implode('&', $this->query);
    }

    /**
     * @see UrlArchiveInterface::resolve
     */
    public function resolve($url, $as = null)
    {
        if (empty($url)) {
            $url = $this->buildUrl();
        } elseif (!preg_match('#^[a-zA-Z]+://#', $url)) {
            $baseItems = parse_url($this->buildUrl());
            $relItems = parse_url($url);

            $resultItems = [];
            $skipBase = false;
            foreach ([
                'scheme',
                'host',
                'port',
                'user',
                'pass',
                'path',
                'query',
                'fragment'
            ] as $itemName) {
                $itemValue = null;
                if (!$skipBase) {
                    $itemValue = $baseItems[$itemName];
                }
                if (isset($relItems[$itemName])) {
                    $itemValue = $relItems[$itemName];
                    $skipBase = true;

                    if ($itemName == 'path' && $itemValue{0} != '/') {
                        $itemValue = $baseItems['path'] . '/../' . $itemValue;
                    }
                }

                if (!is_null($itemValue)) {
                    $resultItems[$itemName] = $itemValue;
                }
            }

            if ($resultItems['path']) {
                $resultItems['path'] = static::removePathDotSegments($resultItems['path']);
            }

            // unparse url
            $url = '';
            if ($resultItems['scheme']) {
                $url .= $resultItems['scheme'] . ':';
            }

            if ($resultItems['host']) {
                $url .= '//';

                if (isset($resultItems['user'])) {
                    $url .= $resultItems['user'];
                    if (isset($resultItems['pass'])) {
                        $url .= ':' . $resultItems['pass'];
                    }
                    $url .= '@';
                }

                $url .= $resultItems['host'];
                if ($resultItems['port']) {
                    $url .= ':' . $resultItems['port'];
                }
            }

            if (isset($resultItems['path'])) {
                $url .= $resultItems['path'];
            }
            if (isset($resultItems['query'])) {
                $url .= '?' . $resultItems['query'];
            }
            if (isset($resultItems['fragment'])) {
                $url .= '#' . $resultItems['fragment'];
            }
        }

        if (null === $as) {
            return self::fromString($url);
        } elseif ('string' == $as) {
            return $url;
        } else {
            if (!is_string($as)) {
                throw new \InvalidArgumentException(
                    'Invalid argument for UrlArchive::resolve(), the class name must be a string'
                );
            }

            $implements = class_implements($as, true);

            if (!in_array(UrlArchiveInterface::class, $implements)) {
                throw new \InvalidArgumentException(
                    'Invalid argument for UrlArchive::resolve(), the specified class must implement'
                    . 'Serps\Core\Url\UrlArchiveInterface'
                );
            }

            return call_user_func([$as, 'fromString'], $url);
        }
    }

    /**
     * Remove any extra dot segments (/../, /./) from a path
     *
     * Algorithm is adapted from RFC-3986 section 5.2.4
     * (@link http://tools.ietf.org/html/rfc3986#section-5.2.4)
     *
     * @todo   consider optimizing
     *
     * @param  string $path
     * @return string
     */
    public static function removePathDotSegments($path)
    {
        $output = '';
        while ($path) {
            if ($path == '..' || $path == '.') {
                break;
            }
            switch (true) {
                case ($path == '/.'):
                    $path = '/';
                    break;
                case ($path == '/..'):
                    $path   = '/';
                    $lastSlashPos = strrpos($output, '/', -1);
                    if (false === $lastSlashPos) {
                        break;
                    }
                    $output = substr($output, 0, $lastSlashPos);
                    break;
                case (substr($path, 0, 4) == '/../'):
                    $path   = '/' . substr($path, 4);
                    $lastSlashPos = strrpos($output, '/', -1);
                    if (false === $lastSlashPos) {
                        break;
                    }
                    $output = substr($output, 0, $lastSlashPos);
                    break;
                case (substr($path, 0, 3) == '/./'):
                    $path = substr($path, 2);
                    break;
                case (substr($path, 0, 2) == './'):
                    $path = substr($path, 2);
                    break;
                case (substr($path, 0, 3) == '../'):
                    $path = substr($path, 3);
                    break;
                default:
                    $slash = strpos($path, '/', 1);
                    if ($slash === false) {
                        $seg = $path;
                    } else {
                        $seg = substr($path, 0, $slash);
                    }
                    $output .= $seg;
                    $path    = substr($path, strlen($seg));
                    break;
            }
        }
        return $output;
    }
}
