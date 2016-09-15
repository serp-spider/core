<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Media;

use Serps\Exception;

class MediaFactory
{

    /**
     * Helper that creates the media object given a string from a html src attribute
     * @param $src
     * @return MediaInterface
     */
    public static function createMediaFromSrc($src)
    {
        if (self::startsWith($src, 'http://', false) || self::startsWith($src, 'https://', false)) {
            // TODO file:// security issue ? Give an option to allow file://
            return new File($src);
        } elseif (preg_match('/^data:(.*);base64,(.*)/', $src, $matches)) {
            return new Base64($matches[2]);
        } else {
            throw new Exception('Unknown src media: ' . $src);
        }
    }

    private static function startsWith($string, $substring, $caseSensitive = true)
    {
        $substringLength = strlen($substring);
        $startOfStr = substr($string, 0, $substringLength);
        if (!$caseSensitive) {
            $substring = strtolower($substring);
            $startOfStr = strtolower($startOfStr);
        }
        return (string) $substring === $startOfStr;
    }
}
