<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Cookie;

class Cookie
{

    protected $name;
    protected $value;
    protected $flags;
    protected $host;
    protected $path;

    /**
     * @param $name
     * @param $value
     * @param $host
     * @param $path
     * @param $flags
     */
    public function __construct($name, $value, $host, $path, $flags)
    {
        $this->name = $name;
        $this->value = $value;
        $this->flags = $flags;
        $this->host = $host;
        $this->path = $path;
    }

    protected function getFlag($flag, $default = null)
    {
        return isset($this->flags[$flag]) ? $this->flags[$flag] : $default;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * @return mixed
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    public function getExpire()
    {
        return $this->getFlag('expires');
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    public function getDomain()
    {
        return $this->getFlag('domain');
    }
}
