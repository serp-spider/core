<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core;

use Serps\Core\Url;

class UrlArchive implements Url\UrlArchiveInterface
{
    use Url\UrlArchiveTrait;

    public function __construct(
        $scheme = null,
        $host = null,
        $path = null,
        array $query = [],
        $hash = null,
        $port = null,
        $user = null,
        $pass = null
    ) {
        $this->initWithDefaults(
            $scheme,
            $host,
            $path,
            $query,
            $hash,
            $port,
            $user,
            $pass
        );
    }

    /**
     * @inheritdoc
     */
    public static function build(
        $scheme = null,
        $host = null,
        $path = null,
        array $query = [],
        $hash = null,
        $port = null,
        $user = null,
        $pass = null
    ) {
        return new static(
            $scheme,
            $host,
            $path,
            $query,
            $hash,
            $port,
            $user,
            $pass
        );
    }
}
