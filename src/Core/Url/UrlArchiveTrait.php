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

    protected $hash;
    protected $path;
    protected $scheme;

    /**
     * @var QueryParam[]
     */
    protected $query = [];

    /**
     * host name e.g: ``www.example.com``
     */
    protected $host;

    protected $user;
    protected $pass;
    protected $port;

    public function __construct(
        $host,
        $path = null,
        $scheme = null,
        array $query = [],
        $hash = null,
        $port = null,
        $user = null,
        $pass = null
    ) {

        $this->host = $host;
        $this->scheme = $scheme ? $scheme : 'https';
        $this->path = $path ;
        $this->hash = $hash;
        $this->port = (int) $port;
        $this->user = $user;
        $this->pass = $pass;

        $this->query = [];
        foreach ($query as $k => $v) {
            if (is_object($v)) {
                if ($v instanceof QueryParam) {
                    $this->query[$v->getName()] = clone $v;
                } else {
                    throw new \InvalidArgumentException('invalid query param item');
                }
            } else {
                $this->query[$k] = new QueryParam($k, $v);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public static function build(
        $scheme = null,
        $host = null,
        $path = null,
        array $query = [],
        $hash = null,
        $port = null,
        $user = null,
        $pass = null
    ) {
        return new static(
            $host,
            $path,
            $scheme,
            $query,
            $hash,
            $port,
            $user,
            $pass
        );
    }


    public static function fromArray(array $urlItems)
    {

        $query = [];
        if (isset($urlItems['query'])) {
            parse_str($urlItems['query'], $baseQuery);
            foreach ($baseQuery as $param => $value) {
                $query[$param] = new QueryParam($param, $value);
            }
        }

        return static::build(
            isset($urlItems['scheme']) ? $urlItems['scheme'] : null,
            isset($urlItems['host']) ? $urlItems['host'] : null,
            isset($urlItems['path']) ? $urlItems['path'] : null,
            $query,
            isset($urlItems['fragment']) ? $urlItems['fragment'] : null,
            isset($urlItems['port']) ? $urlItems['port'] : null,
            isset($urlItems['user']) ? $urlItems['user'] : null,
            isset($urlItems['path']) ? $urlItems['path'] : null
        );
    }

    /**
     * Builds an url instance from an url string
     * @param string $url the url to parse
     * @return static
     */
    public static function fromString($url)
    {

        // Normally a URI must be ASCII, however. However, often it's not and
        // parse_url might corrupt these strings.
        //
        // For that reason we take any non-ascii characters from the uri and
        // uriencode them first.
        //
        // code from https://github.com/fruux/sabre-uri
        $url = preg_replace_callback(
            '/[^[:ascii:]]/u',
            function ($matches) {
                return rawurlencode($matches[0]);
            },
            $url
        );

        $urlItems = parse_url($url);
        return static::fromArray($urlItems);
    }

    /**
     * @return QueryParam[]
     */
    public function getParams()
    {
        return $this->query;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPassword()
    {
        return $this->pass;
    }

    public function getPort()
    {
        if (empty($this->port)) {
            return $this->getScheme() === 'https' ? 443 : 80;
        } else {
            return $this->port;
        }
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
        $scheme = $this->getScheme();
        $uri = $scheme . '://';

        if ($user=$this->getUser()) {
            $uri .= $user;
            if ($pass=$this->getPassword()) {
                $uri .= ':' . $pass;
            }
            $uri .= '@';
        }

        $uri .= $this->getHost();

        $port = $this->getPort();
        if ($port) {
            if (('http' === $scheme && 80 !== $this->getPort())
                || ('https' === $scheme && 443 !== $this->getPort())
            ) {
                $uri .= ':' . $port;
            }
        }

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
}
