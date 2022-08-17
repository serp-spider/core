<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Serp;

use Closure;
use Serps\Core\Serp\ResultDataInterface;

class BaseResult implements ResultDataInterface
{

    protected $types;
    protected $data;
    protected $nodePath                   = '';
    private $serpFeatureHasPosition     = false;
    private $serpFeatureHasSidePosition = false;

    public function __construct(
        $types,
        array $data = [],
        ?\DOMElement $domElement = null,
        $hasSerpFeaturePosition = false,
        $hasSideSerpFeaturePosition = false
    )
    {
        $this->types = is_array($types) ? $types : [$types];
        $this->data = $data;
        if ($domElement) {
            $this->nodePath = $domElement->getNodePath();
        }

        $this->serpFeatureHasPosition = $hasSerpFeaturePosition;
        $this->serpFeatureHasSidePosition = $hasSideSerpFeaturePosition;
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
        if ($data instanceof Closure) {
            $data = call_user_func($data, $this);
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
            $datum = $this->getDataValue($k);
            if (is_array($datum) || (is_object($datum) && $datum instanceof ResultSetInterface)) {
                // make sur datum is an array
                $baseDatum = $datum;
                $datum = [];

                foreach ($baseDatum as $subK => $subV) {
                    if (is_object($subV) && $subV instanceof ResultDataInterface) {
                        $datum[$subK] = $subV->getData();
                    } else {
                        $datum[$subK] = $subV;
                    }
                }
            } elseif (is_object($datum) && $datum instanceof ResultDataInterface) {
                $datum = $datum->getData();
            }
            $data[$k] = $datum;
        }
        return $data;
    }

    public function getNodePath() {
        return $this->nodePath;
    }

    public function serpFeatureHasPosition() {
        return $this->serpFeatureHasPosition;
    }

    public function serpFeatureHasSidePosition() {
        return $this->serpFeatureHasSidePosition;
    }
}
