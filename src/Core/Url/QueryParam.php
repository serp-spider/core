<?php
/**
 * @license see LICENSE
 */

namespace Serps\Core\Url;

class QueryParam
{

    /**
     * @var string name of the parameter
     */
    protected $name;

    /**
     * @var string Value of the parameter
     */
    protected $value;

    /**
     * By default params value are encoded for url. If raw is true no additional processing is applied
     * @var bool
     */
    protected $raw;

    /**
     * QueryParam constructor.
     * @param $raw
     * @param $name
     * @param $value
     */
    public function __construct($name, $value, $raw = false)
    {
        $this->raw = $raw;
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        if (!$this->isRaw()) {
            if (is_string($this->value)) {
                return urlencode($this->value);
            } else {
                return $this->value;
            }
        }
        return $this->value;
    }

    public function getRawValue()
    {
        return $this->value;
    }

    /**
     * @return boolean
     */
    public function isRaw()
    {
        return $this->raw;
    }


    /**
     * Generate the paramatere to be appended to the url
     * @return string the parameter on this format: ``name=value``
     */
    public function generate()
    {
        return $this->queryItemToString($this->getValue());
    }

    private function queryItemToString($value)
    {

        if (is_string($value)) {
            if (strlen($value) > 0) {
                return $this->getName() . '=' . $value;
            }
        } elseif (is_array($value)) {
            if (empty($value)) {
                return $this->getName() . '[]';
            } else {
                return $this->arrayToStringRecursive($this->getName(), $value);
            }
        }

        return (string) $this->getName();
    }

    private function arrayToStringRecursive($currentKey, $dataArray)
    {
        $data = [];
        foreach ($dataArray as $k => $v) {
            $key = "${currentKey}[${k}]";
            if (is_array($v)) {
                $str = $this->arrayToStringRecursive($key, $v);
            } else {
                if (!$this->isRaw()) {
                    $v = urlencode($v);
                }
                $str = $key . '=' . $v;
            }
            $data[] = $str;
        }
        return implode('&', $data);
    }

    public function __toString()
    {
        return $this->generate();
    }

    public function __clone()
    {
        return new self($this->getName(), $this->getRawValue(), $this->isRaw());
    }
}
