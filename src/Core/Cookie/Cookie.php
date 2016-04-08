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
    /**
     * @param $name
     * @param $value
     * @param $flags
     */
    public function __construct($name, $value, $flags)
    {
        $this->name = $name;
        $this->value = $value;
        $this->flags = $flags;
    }

    protected function getFlag($flag, $default = null)
    {
        return isset($this->flags[$flag]) ?
            $this->flags[$flag]
            : $default;
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
    public function getPath()
    {
        return $this->getFlag('path', '/');
    }

    public function getDomain()
    {
        return $this->getFlag('domain');
    }

    public function getExpires()
    {
        return $this->getFlag('expires');
    }

    public function matchesPath($path)
    {

        $cookiePath = $this->getPath();

        // RFC6265 http://tools.ietf.org/search/rfc6265#section-5.1.4
        // A request-path path-matches a given cookie-path if at least one of
        // the following conditions holds:
        // o  The cookie-path and the request-path are identical.
        if ($path == $cookiePath) {
            return true;
        }
        $pos = stripos($path, $cookiePath);
        if ($pos === 0) {
            // o  The cookie-path is a prefix of the request-path, and the last
            // character of the cookie-path is %x2F ("/").
            if (substr($cookiePath, -1, 1) === '/') {
                return true;
            }
            // o  The cookie-path is a prefix of the request-path, and the first
            // character of the request-path that is not included in the cookie-
            // path is a %x2F ("/") character.
            if (substr($path, strlen($cookiePath), 1) === '/') {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if the cookie matches a domain value
     *
     * @param string $domain Domain to check against
     *
     * @return bool
     */
    public function matchesDomain($domain)
    {
        // Remove the leading '.' as per spec in RFC 6265: http://tools.ietf.org/html/rfc6265#section-5.2.3
        $cookieDomain = ltrim($this->getDomain(), '.');
        // Domain not set or exact match.
        if (!$cookieDomain || !strcasecmp($domain, $cookieDomain)) {
            return true;
        }
        // Matching the subdomain according to RFC 6265: http://tools.ietf.org/html/rfc6265#section-5.1.3
        if (filter_var($domain, FILTER_VALIDATE_IP)) {
            return false;
        }
        return (bool) preg_match('/\.' . preg_quote($cookieDomain, '/') . '$/i', $domain);
    }

    /**
     * Check if the cookie is expired
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->getExpires() && time() > $this->getExpires();
    }

    /**
     * Check if the cookie is valid according to RFC 6265
     *
     * @return bool|string Returns true if valid or an error message if invalid
     */
    public function validate()
    {
        // Names must not be empty, but can be 0
        $name = $this->getName();
        if (empty($name) && !is_numeric($name)) {
            return 'The cookie name must not be empty';
        }
        // Check if any of the invalid characters are present in the cookie name
        if (strpbrk($name, self::getInvalidCharacters()) !== false) {
            return 'The cookie name must not contain invalid characters: ' . $name;
        }
        // Value must not be empty, but can be 0
        $value = $this->getValue();
        if (empty($value) && !is_numeric($value)) {
            return 'The cookie value must not be empty';
        }
        // Domains must not be empty, but can be 0
        // A "0" is not a valid internet domain, but may be used as server name in a private network
        $domain = $this->getDomain();
        if (empty($domain) && !is_numeric($domain)) {
            return 'The cookie domain must not be empty';
        }
        return true;
    }

    /**
     * @var string ASCII codes not valid for for use in a cookie name
     *
     * Cookie names are defined as 'token', according to RFC 2616, Section 2.2
     * A valid token may contain any CHAR except CTLs (ASCII 0 - 31 or 127)
     * or any of the following separators
     */
    protected static $invalidCharString;

    /**
     * Gets an array of invalid cookie characters
     *
     * @return array
     */
    protected static function getInvalidCharacters()
    {
        if (!self::$invalidCharString) {
            self::$invalidCharString = implode('', array_map('chr', array_merge(
                range(0, 32),
                [34, 40, 41, 44, 47],
                [58, 59, 60, 61, 62, 63, 64, 91, 92, 93, 123, 125, 127]
            )));
        }
        return self::$invalidCharString;
    }

    public function getDiscard()
    {
        return $this->getFlag('discard', false);
    }

    public function getSecure()
    {
        return $this->getFlag('secure', false);
    }

    /**
     * Formats the cookies to be set in the Cookie header
     *
     * @return string the formatted cookie with this format: name=value
     */
    public function formatForCookieHeader()
    {
        return $this->getName() . '=' . $this->getValue();
    }


    /**
     * Formats the cookie into a json string to make it exportable.
     * @return string a json representation of the cookie
     */
    public function toJson()
    {
        $data = [
            'name' => $this->getName(),
            'value' => $this->getValue(),
            'flags' => [
                'path' => $this->getPath(),
                'domain' => $this->getDomain(),
                'expires' => $this->getExpires(),
                'discard' => $this->getDiscard(),
                'secure'  => $this->getSecure(),

            ]
        ];
        return json_encode($data);
    }
}
