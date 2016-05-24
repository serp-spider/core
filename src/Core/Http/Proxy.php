<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Http;

use Serps\Exception;

class Proxy implements ProxyInterface
{

    protected $ip;
    protected $port;
    protected $type;
    protected $type; // Proxy Types: HTTP, SOCKS4 and SOCKS5
    protected $user;
    protected $password;
    protected $scheme;

    public function __construct($ip, $port, $type = null, $user = null, $password = null, $scheme = null)
    {
        $this->ip       = $ip;
        $this->port     = $port;
        $this->type     = $type;
        $this->type     = $type;
        $this->user     = $user;
        $this->password = $password;
        $this->scheme   = $scheme;

    }

    public function getIp()
    {
        return $this->ip;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getType()
    {
        return strtoupper($this->type);
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public static function createFromString($proxy)
    {
        $proxyPieces = explode('@', $proxy);
        if (count($proxyPieces) == 2) {
            $authPieces = explode(':', $proxyPieces[0]);
            if (count($authPieces) > 2) {
                throw new Exception('Bad proxy string. Expected format: [user[:passsword]@]ip:port');
            }
            if (!isset($authPieces[1])) {
                $authPieces[1] = null;
            }
            $hostPieces = explode(':', $proxyPieces[1]);
            if (count($hostPieces) !== 2) {
                throw new Exception('Bad proxy string. Expected format: [user[:passsword]@]ip:port');
            }
        } elseif (count($proxyPieces) == 1) {
            $authPieces = [null, null];
            $hostPieces = explode(':', $proxyPieces[0]);
        } else {
            throw new Exception('Bad proxy string. Expected format: [user[:passsword]@]ip:port');
        }
        $options['login']    = $authPieces[0];
        $options['password'] = $authPieces[1];
        return new self($hostPieces[0], $hostPieces[1], $authPieces[0], $authPieces[1]);
    }

    public function __toString()
    {
        $proxy = $this->getIp() . ':' . $this->getPort();
        if ($user = $this->getUser()) {
            if ($password = $this->getPassword()) {
                $user .= ':' . $password;
            }
            $proxy = $user . '@' . $proxy;
        }

        if ($this->scheme) {
            $proxy = $this->scheme . '://' . $proxy;
        }

        return $proxy;
    }
}
