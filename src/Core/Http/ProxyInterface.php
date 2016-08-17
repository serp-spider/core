<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Http;

interface ProxyInterface
{
    public function getHost();
    public function getPort();

    public function getUser();
    public function getPassword();

    public function getType();
}
