<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core;

use Serps\Core\Url\AlterableUrlInterface;
use Serps\Core\Url\AlterableUrlTrait;
use Serps\Core\Url\QueryParam;

class Url implements AlterableUrlInterface
{
    use AlterableUrlTrait;

    /**
     * Check if the URI is an absolute or relative URI
     *
     * @return bool
     */
    public function isAbsolute()
    {
        return ($this->scheme !== null);
    }

    /**
     * Convert a relative URI into an absolute URI using a base absolute URI as
     * a reference.
     *
     * This is similar to merge() - only it uses the supplied URI as the
     * base reference instead of using the current URI as the base reference.
     *
     * Merging algorithm is adapted from RFC-3986 section 5.2
     * (@link http://tools.ietf.org/html/rfc3986#section-5.2)
     *
     * @param  Url|string $baseUri
     * @throws InvalidArgumentException
     * @return Url
     */
    public function resolveByBaseUri($baseUri)
    {
        // Ignore if URI is absolute
        if ($this->isAbsolute()) {
            return $this;
        }
        if (is_string($baseUri)) {
            $baseUri = static::fromString($baseUri);
        } elseif (!$baseUri instanceof Url) {
            throw new InvalidArgumentException(
                'Provided base URI must be a string or a Url object'
            );
        }
        // Merging starts here...
        if ($this->getHost()) {
            $this->setPath(static::removePathDotSegments($this->getPath()));
        } else {
            $basePath = $baseUri->getPath();
            $relPath  = $this->getPath();
            if (!$relPath) {
                $this->setPath($basePath);
                if (!$this->getParams()) {
                    $this->setParams($baseUri->getParams());
                }
            } else {
                if (substr($relPath, 0, 1) == '/') {
                    $this->setPath(static::removePathDotSegments($relPath));
                } else {
                    if ($baseUri->getHost() && !$basePath) {
                        $mergedPath = '/';
                    } else {
                        $mergedPath = substr($basePath, 0, strrpos($basePath, '/') + 1);
                    }
                    $this->setPath(static::removePathDotSegments($mergedPath . $relPath));
                }
            }
            $this->setHost($baseUri->getHost());
        }
        $this->setScheme($baseUri->getScheme());
        return $this;
    }

    /**
     * Remove any extra dot segments (/../, /./) from a path
     *
     * Algorithm is adapted from RFC-3986 section 5.2.4
     * (@link http://tools.ietf.org/html/rfc3986#section-5.2.4)
     *
     * @todo   consider optimizing
     *
     * @param  string $path
     * @return string
     */
    public static function removePathDotSegments($path)
    {
        $output = '';
        while ($path) {
            if ($path == '..' || $path == '.') {
                break;
            }
            switch (true) {
                case ($path == '/.'):
                    $path = '/';
                    break;
                case ($path == '/..'):
                    $path   = '/';
                    $lastSlashPos = strrpos($output, '/', -1);
                    if (false === $lastSlashPos) {
                        break;
                    }
                    $output = substr($output, 0, $lastSlashPos);
                    break;
                case (substr($path, 0, 4) == '/../'):
                    $path   = '/' . substr($path, 4);
                    $lastSlashPos = strrpos($output, '/', -1);
                    if (false === $lastSlashPos) {
                        break;
                    }
                    $output = substr($output, 0, $lastSlashPos);
                    break;
                case (substr($path, 0, 3) == '/./'):
                    $path = substr($path, 2);
                    break;
                case (substr($path, 0, 2) == './'):
                    $path = substr($path, 2);
                    break;
                case (substr($path, 0, 3) == '../'):
                    $path = substr($path, 3);
                    break;
                default:
                    $slash = strpos($path, '/', 1);
                    if ($slash === false) {
                        $seg = $path;
                    } else {
                        $seg = substr($path, 0, $slash);
                    }
                    $output .= $seg;
                    $path    = substr($path, strlen($seg));
                    break;
            }
        }
        return $output;
    }

    /**
     * Merge a base URI and a relative URI into a new URI object
     *
     * This convenience method wraps ::resolveByBaseUri() to allow users to quickly
     * create new absolute URLs without the need to instantiate and clone
     * URI objects.
     *
     * If objects are passed in, none of the passed objects will be modified.
     *
     * @param  Url|string $baseUri
     * @param  Url|string $relativeUri
     * @return Url
     */
    public static function merge($baseUri, $relativeUri)
    {
        if (is_string($relativeUri)) {
            $uri = static::fromString($relativeUri);
        } elseif (!$relativeUri instanceof Url) {
            throw new InvalidArgumentException(
                'Provided relative URI must be a string or a Url object'
            );
        }
        return $uri->resolveByBaseUri($baseUri);
    }
}
