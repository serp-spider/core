<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core;

use Serps\Core\Url\AlterableUrlInterface;
use Serps\Core\Url\AlterableUrlTrait;
use Serps\Core\Url\QueryParam;

class Url extends UrlArchive implements AlterableUrlInterface
{
    use AlterableUrlTrait;
}
