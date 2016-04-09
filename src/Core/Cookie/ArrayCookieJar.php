<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Cookie;

use Psr\Http\Message\RequestInterface;
use Serps\Exception\InvalidCookieException;

/**
 * Cookie jar inspired by:
 * https://github.com/guzzle/guzzle3/blob/master/src/Guzzle/Plugin/Cookie/CookieJar/ArrayCookieJar.php
 */
class ArrayCookieJar implements CookieJarInterface
{

    /**
     * @var Cookie[]
     */
    protected $cookies = [];
    protected $strictMode;

    /**
     * ArrayCookieJar constructor.
     * @param bool $strictMode pass to true to throw an exception when an invalid cookie is added
     */
    public function __construct($strictMode = false)
    {
        $this->strictMode = $strictMode;
    }

    /**
     * @return mixed
     */
    public function getStrictMode()
    {
        return $this->strictMode;
    }

    /**
     * @param bool $strictMode pass to true to throw an exception when an invalid cookie is added
     */
    public function setStrictMode($strictMode)
    {
        $this->strictMode = $strictMode;
    }


    /**
     * @inheritdoc
     */
    public function set(Cookie $cookie)
    {
        $result = $cookie->validate();
        if ($result !== true) {
            if ($this->strictMode) {
                throw new InvalidCookieException($result);
            } else {
                $this->removeCookieIfEmpty($cookie);
                return false;
            }
        }

        // Resolve conflicts with previously set cookies
        foreach ($this->cookies as $i => $c) {
            // Two cookies are identical, when their path, domain and name are identical
            if ($c->getPath() != $cookie->getPath() ||
                $c->getDomain() != $cookie->getDomain() ||
                $c->getName() != $cookie->getName()
            ) {
                continue;
            }
            // The previously set cookie is a discard cookie and this one is not so allow the new cookie to be set
            if (!$cookie->getDiscard() && $c->getDiscard()) {
                unset($this->cookies[$i]);
                continue;
            }
            // If the new cookie's expiration is further into the future, then replace the old cookie
            if ($cookie->getExpires() > $c->getExpires()) {
                unset($this->cookies[$i]);
                continue;
            }
            // If the value has changed, we better change it
            if ($cookie->getValue() !== $c->getValue()) {
                unset($this->cookies[$i]);
                continue;
            }
            // The cookie exists, so no need to continue
            return false;
        }
        $this->cookies[] = $cookie;
        return true;
    }

    /**
     * If a cookie already exists and the server asks to set it again with a null value, the
     * cookie must be deleted.
     *
     * @param Cookie $cookie
     */
    private function removeCookieIfEmpty(Cookie $cookie)
    {
        $cookieValue = $cookie->getValue();
        if ($cookieValue === null || $cookieValue === '') {
            $this->remove($cookie->getDomain(), $cookie->getPath(), $cookie->getName());
        }
    }

    /**
     * @inheritdoc
     */
    public function remove($domain = null, $path = null, $name = null)
    {
        $cookies = $this->all($domain, $path, $name, false, false);
        $this->cookies = array_filter($this->cookies, function (Cookie $cookie) use ($cookies) {
            return !in_array($cookie, $cookies, true);
        });
    }

    /**
     * @inheritdoc
     */
    public function removeTemporary()
    {
        $this->cookies = array_filter($this->cookies, function (Cookie $cookie) {
            return !$cookie->getDiscard() && $cookie->getExpires();
        });
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeExpired()
    {
        $currentTime = time();
        $this->cookies = array_filter($this->cookies, function (Cookie $cookie) use ($currentTime) {
            return !$cookie->getExpires() || $currentTime < $cookie->getExpires();
        });
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getMatchingCookies(RequestInterface $request)
    {
         // Find cookies that match this request
        $cookies = $this->all($request->getUri()->getHost(), $request->getUri()->getPath());
        // Remove ineligible cookies
        foreach ($cookies as $index => $cookie) {
            if ($cookie->getSecure() && $request->getUri()->getScheme() != 'https') {
                unset($cookies[$index]);
            }
        };
        return $cookies;
    }

    /**
     * @inheritdoc
     */
    public function all($domain = null, $path = null, $name = null, $skipDiscardable = false, $skipExpired = true)
    {
        return array_values(array_filter($this->cookies, function (Cookie $cookie) use (
            $domain,
            $path,
            $name,
            $skipDiscardable,
            $skipExpired
        ) {
            return false === (($name && $cookie->getName() != $name) ||
                ($skipExpired && $cookie->isExpired()) ||
                ($skipDiscardable && ($cookie->getDiscard() || !$cookie->getExpires())) ||
                ($path && !$cookie->matchesPath($path)) ||
                ($domain && !$cookie->matchesDomain($domain)));
        }));
    }

    public function export()
    {
        $cookies = $this->all(null, null, null, false, false);
        $data = [];
        foreach ($cookies as $cookie) {
            $data[] = $cookie->export();
        }
        return $data;
    }

    public function import($data)
    {
        foreach ($data as $cookieData) {
            $this->set(new Cookie($cookieData['name'], $cookieData['value'], $cookieData['flags']));
        }
    }
}
