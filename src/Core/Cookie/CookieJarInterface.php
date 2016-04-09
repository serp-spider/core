<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Cookie;

use Psr\Http\Message\RequestInterface;

/**
 * A cookie jar contains cookies and is able to retrieve cookies for a given request
 *
 * Highly inspired by:
 * https://github.com/guzzle/guzzle3/blob/master/src/Guzzle/Plugin/Cookie/CookieJar/CookieJarInterface.php
 */
interface CookieJarInterface
{

    /**
     * Set a cookie
     * @param Cookie $cookie
     * @return mixed
     */
    public function set(Cookie $cookie);


    /**
     * Remove cookies currently held in the Cookie cookieJar.
     *
     * Invoking this method without arguments will empty the whole Cookie cookieJar.  If given a $domain argument only
     * cookies belonging to that domain will be removed. If given a $domain and $path argument, cookies belonging to
     * the specified path within that domain are removed. If given all three arguments, then the cookie with the
     * specified name, path and domain is removed.
     *
     * @param string $domain Set to clear only cookies matching a domain
     * @param string $path   Set to clear only cookies matching a domain and path
     * @param string $name   Set to clear only cookies matching a domain, path, and name
     *
     * @return CookieJarInterface
     */
    public function remove($domain = null, $path = null, $name = null);

    /**
     * Discard all temporary cookies.
     *
     * Scans for all cookies in the cookieJar with either no expire field or a true discard flag. To be called when the
     * user agent shuts down according to RFC 2965.
     *
     * @return CookieJarInterface
     */
    public function removeTemporary();

    /**
     * Delete any expired cookies
     *
     * @return CookieJarInterface
     */
    public function removeExpired();

    /**
     * Get cookies matching a request object
     *
     * @param RequestInterface $request Request object to match
     *
     * @return Cookie[]
     */
    public function getMatchingCookies(RequestInterface $request);


    /**
     * Get all of the matching cookies
     *
     * @param string $domain          Domain of the cookie
     * @param string $path            Path of the cookie
     * @param string $name            Name of the cookie
     * @param bool   $skipDiscardable Set to TRUE to skip cookies with the Discard attribute.
     * @param bool   $skipExpired     Set to FALSE to include expired
     *
     * @return Cookie[] the cookies that matched the parameters
     */
    public function all($domain = null, $path = null, $name = null, $skipDiscardable = false, $skipExpired = true);

    /**
     * Exports all cookies to make them serializable
     * @return array
     */
    public function export();

    /**
     * Imports cookies that were export with @see export()
     * @return mixed
     */
    public function import($data);
}
