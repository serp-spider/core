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
            return urlencode($this->value);
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
     * @return string the parameter on this format: ``name=value`` or ``name``
     */
    public function generate()
    {
        $result = $this->getName();
        if (!is_null($this->value)) {
            $result .= '=' . $this->getValue();
        }
        return $result;
    }

    public function __toString()
    {
        return $this->generate();
    }
}
