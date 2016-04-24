<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Url;

use Serps\Core\Url\QueryParam;

/**
 * Base interface for UrlArchive
 *
 * @see UrlArchiveTrait
 */
interface UrlArchiveInterface
{

    /**
     * UrlInterface constructor.
     * @param string $host
     * @param string $path
     * @param string $scheme
     * @param QueryParam[] $query
     * @param string $hash
     */
    public function __construct(
        $host,
        $path = '',
        $scheme = 'https',
        array $query = [],
        $hash = ''
    );

    /**
     * @return QueryParam[]
     */
    public function getParams();

    /**
     * Builds an url instance from an url string
     * @param string $url the url to parse
     * @return static
     */
    public static function fromString($url);


    /**
     * Set the scheme.
     * ``foo`` in ``http://www.example.com#foo``
     * @return string
     */
    public function getHash();

    /**
     * Set the scheme.
     * ``some/path`` in ``http://www.example.com/some/path``
     * @return string
     */
    public function getPath();

    /**
     * Get the scheme.
     * ``http`` in ``http://www.example.com``
     * @return string
     */
    public function getScheme();

    /**
     * Get the hostname.
     * ``www.example.com`` in ``http://www.example.com``
     * @return string
     */
    public function getHost();

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getParamValue($name, $default = null);

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function getParamRawValue($name, $default = null);

    /**
     * @param string $name
     * @return bool
     */
    public function hasParam($name);

    /**
     * Get the full uri: ``http://www.example.com/path?param=value#hash``
     * @return string
     */
    public function buildUrl();

    /**
     * Shortcut for @see buildUrl()
     * @return string
     */
    public function __toString();

    /**
     * Get the query string.
     * ``foo=bar&bar=foo`` in ``http://www.example.com?foo=bar&bar=foo``
     * @return string
     */
    public function getQueryString();

    /**
     * @param string $url the absolute or relative url to resolve
     * @param string|null $as the FQCN to create (must be a UrlArchiveInterface),
     * or "string" to return a string or null to use self
     * @return UrlArchiveInterface the generated url
     */
    public function resolve($url, $as = null);
}
