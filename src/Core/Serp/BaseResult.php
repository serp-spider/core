<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Serp;

use Serps\Core\Serp\ResultDataInterface;

class BaseResult implements ResultDataInterface
{

    protected $type;
    protected $data;

    public function __construct($type, array $data)
    {
        $this->type = $type;
        $this->data = $data;
    }

    public function getType()
    {
        return $this->type;
    }

    public function is(...$type)
    {
        return in_array($this->getType(), $type);
    }

    public function getDataValue($name)
    {
        return isset($this->data[$name]) ? $this->data[$name] : null;
    }

    public function getData()
    {
        return $this->data;
    }
}
