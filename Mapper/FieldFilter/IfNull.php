<?php

namespace BCC\AutoMapperBundle\Mapper\FieldFilter;

/**
 * IfNull applies a default value if the original is null.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
class IfNull implements FieldFilterInterface
{
    /**
     * @var mixed
     */
    private $value;
    
    /**
     *
     * @param mixed $value The value
     */
    function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Returns a default value if the original is null
     * 
     * @param mixed $value 
     * @return mixed
     */
    public function filter($value)
    {
        return $value ?: $this->value;
    }
}
