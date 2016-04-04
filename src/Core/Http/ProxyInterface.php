<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Http;

interface ProxyInterface
{
    public function getIp();
    public function getPort();

    public function getUser();
    public function getPassword();

    public function getScheme();
}
