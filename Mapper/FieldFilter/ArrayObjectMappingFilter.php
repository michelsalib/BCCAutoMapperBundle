<?php

namespace BCC\AutoMapperBundle\Mapper\FieldFilter;

class ArrayObjectMappingFilter extends AbstractMappingFilter
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
        if (is_array($value)) {
            $objectFilter = new ObjectMappingFilter($this->className);
            $objectFilter->setMapper($this->getMapper());

            return array_map(function ($item) use ($objectFilter) {
                return $objectFilter->filter($item);
            }, $value);
        }
    }
}