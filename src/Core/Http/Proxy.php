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
    protected $user;
    protected $password;

    /**
     * @param string $ip proxy ip
     * @param int $port proxy port
     * @param string|null $user username if proxy needs credentials
     * @param string|null $password password if proxy needs credentials
     * @param string $type proxy type: one of "HTTP", "HTTPS", "SOCKS4", "SOCKS5". This will be automatically uppercased
     */
    public function __construct($ip, $port, $user = null, $password = null, $type = 'HTTP')
    {
        $this->ip       = $ip;
        $this->port     = $port;
        $this->type     = strtoupper($type);
        $this->user     = $user;
        $this->password = $password;
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
        return $this->type;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public static function createFromString($proxy)
    {

        if (preg_match('#^[a-zA-Z0-9]+://#', $proxy)) {
            list($type, $proxy) = explode('://', $proxy, 2);
        } else {
            $type = 'HTTP';
        }

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
        return new self($hostPieces[0], $hostPieces[1], $authPieces[0], $authPieces[1], $type);
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

        if ($this->type) {
            $proxy = strtolower($this->getType()) . '://' . $proxy;
        }

        return $proxy;
    }
}
