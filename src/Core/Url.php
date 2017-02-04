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
