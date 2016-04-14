<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Url;

use Serps\Core\Url\UrlArchiveInterface;
use Serps\Core\Url\QueryParam;

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
            parse_str($urlItems['query'], $baseQuery);
            foreach ($baseQuery as $param => $value) {
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
     * @inheritdoc
     */
    public function resolve($url, $as = null)
    {
        if (!preg_match('#^[a-zA-Z]+://#', $url)) {
            if ('/' == $url{0}) {
                if ('/' == $url{1}) {
                    $url = $this->getScheme() . ':' . $url;
                } else {
                    $url = $this->getScheme() . '://' . $this->getHost()  . $url;
                }
            } else {
                // TODO ($this->resolve('bar');)
            }
        }

        if (null === $as) {
            return self::fromString($url);
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
}
