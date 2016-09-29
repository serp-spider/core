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
     * @param string $scheme
     * @param string $host
     * @param string $path
     * @param QueryParam[]|array $query
     * @param string $hash
     * @param int $port
     * @param string $user
     * @param string $pass
     * @return static
     */
    public static function build(
        $scheme,
        $host,
        $path = null,
        array $query = [],
        $hash = null,
        $port = null,
        $user = null,
        $pass = null
    );



    /**
     * Builds an url instance from an url string
     * @param string $url the url to parse
     * @return static
     */
    public static function fromString($url);


    /**
     * Builds an url from an array of data, these data are the same as the one returned by the function parse_url:
     * - scheme - e.g. http
     * - host
     * - port
     * - user
     * - pass
     * - path
     * - query
     * - hash
     *
     * Any value can be omitted
     *
     * @param $array
     * @return mixed
     */
    public static function fromArray(array $array);



    /**
     * get query params
     * @return QueryParam[]
     */
    public function getParams();

    /**
     * get the auth username
     * @return mixed
     */
    public function getUser();

    /**
     * get the auth password
     * @return mixed
     */
    public function getPass();

    /**
     * get the port  number (default 80)
     * @return int
     */
    public function getPort();


    /**
     * get the hash.
     * ``foo`` in ``http://www.example.com#foo``
     * @return string
     */
    public function getHash();

    /**
     * Get the path.
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
     * Get the authority of the url
     * @return mixed
     */
    public function getAuthority();

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
     * or null to use self
     * @return UrlArchiveInterface the generated url
     */
    public function resolve($url, $as = null);


    /**
     * @param string $url the absolute or relative url to resolve
     * @return string the generated url
     */
    public function resolveAsString($url);
}
