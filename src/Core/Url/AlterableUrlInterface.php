<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Url;

/**
 * Base interface
 */
interface AlterableUrlInterface extends UrlArchiveInterface
{

     /**
     * Set the scheme.
     * ``foo`` in ``http://www.example.com#foo``
     * @param string $hash
     * @return $this
     */
    public function setHash($hash);

    /**
     * Set the path.
     * ``/some/path`` in ``http://www.example.com/some/path``
     * @param string $path
     * @return $this
     */
    public function setPath($path);

    /**
     * Set the port
     * @param int $port
     */
    public function setPort($port);

    /**
     * Set the user for auth
     * @param string $user
     */
    public function setUser($user);

    /**
     * Set the pass for auth
     * @param string $pass
     */
    public function setPass($pass);

    /**
     * Set the scheme.
     * ``http`` in ``http://www.example.com``
     * @param string $scheme
     * @return $this
     */
    public function setScheme($scheme);

    /**
     * Set the hostname.
     * ``www.example.com`` in ``http://www.example.com``
     * @param string $host the hostname
     * @return $this
     */
    public function setHost($host);

    /**
     * Add a parameter to the URL.
     * Parameter will come after the ``?`` e.g: ``http://example.com?param=value&param2=value``
     * @param string $name name of the parameter
     * @param string $value value of the parameter
     * @param bool $raw by default params are encoded to be url (``foo bar`` becomes ``foo+bar``) pass it to true
     * to disable this encoding
     * @return $this
     */
    public function setParam($name, $value, $raw = false);

    /**
     * Remove the given parameter
     * @param string $name name of the parameter to remove
     * @return $this;
     */
    public function removeParam($name);
}
