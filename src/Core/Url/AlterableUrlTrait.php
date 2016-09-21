<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Url;

trait AlterableUrlTrait
{

    use UrlArchiveTrait;

    /**
     * Set the scheme.
     * ``foo`` in ``http://www.example.com#foo``
     * @param string $hash
     * @return $this
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
        return $this;
    }

    /**
     * Set the path.
     * ``/some/path`` in ``http://www.example.com/some/path``
     * @param string $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Set the port
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * Set the user for auth
     * @param string $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * Set the pass for auth
     * @param string $pass
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
    }


    /**
     * Set the scheme.
     * ``http`` in ``http://www.example.com``
     * @param string $scheme
     * @return $this
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
    }


    /**
     * Set the hostname.
     * ``www.example.com`` in ``http://www.example.com``
     * @param string $host the hostname
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }


    /**
     * Add a parameter to the URL.
     * Parameter will come after the ``?`` e.g: ``http://example.com?param=value&param2=value``
     * @param string $name name of the parameter
     * @param string $value value of the parameter
     * @param bool $raw by default params are encoded to be url (``foo bar`` becomes ``foo+bar``) pass it to true
     * to disable this encoding
     * @return $this
     */
    public function setParam($name, $value, $raw = false)
    {

        $this->query[$name] = new QueryParam($name, $value, $raw);
        return $this;
    }

    /**
     * Remove the given parameter
     * @param string $name name of the parameter to remove
     * @return $this;
     */
    public function removeParam($name)
    {
        if (isset($this->query[$name])) {
            unset($this->query[$name]);
        }
        return $this;
    }
}
