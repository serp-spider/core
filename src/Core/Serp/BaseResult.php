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
        $data = isset($this->data[$name]) ? $this->data[$name] : null;
        if (is_callable($data)) {
            $data = call_user_func($data);
            $this->data[$name] = $data;
            return $this->getDataValue($name);
        }
        return $data;
    }
}
