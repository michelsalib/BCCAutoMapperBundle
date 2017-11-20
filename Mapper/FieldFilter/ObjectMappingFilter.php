<?php

namespace BCC\AutoMapperBundle\Mapper\FieldFilter;

/**
 * Filter object value and apply mapping to given className
 *
 * @author Jorge Garcia Ramos <jorgegr89@gmail.com>
 */
class ObjectMappingFilter extends AbstractMappingFilter
{
    /**
     * Applies the filter to a given value.
     *
     * @param $value mixed The value to filter
     *
     * @return mixed The filtered value
     */
    function filter($value)
    {
        if ($value) {
            return $this->getMapper()->map($value, $this->className);
        }
    }
}