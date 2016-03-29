<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Serp;

use Serps\Core\Serp\ResultDataInterface;

class BaseResult implements ResultDataInterface
{

    protected $types;
    protected $data;

    public function __construct($types, array $data = [])
    {
        $this->types = is_array($types) ? $types : [$types];
        $this->data = $data;
    }

    public function getTypes()
    {
        return $this->types;
    }

    /**
     * @param array ...$type
     * @return bool
     */
    public function is($types)
    {
        $types = func_get_args();

        $testedTypes = $this->getTypes();

        foreach ($types as $type) {
            if (in_array($type, $testedTypes)) {
                return true;
            }
        }
        return false;
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


    public function __get($name)
    {
        return $this->getDataValue($name);
    }

    public function getData()
    {
        $data = [];
        foreach ($this->data as $k => $v) {
            $data[$k] = $this->getDataValue($k);
        }
        return $data;
    }
}
