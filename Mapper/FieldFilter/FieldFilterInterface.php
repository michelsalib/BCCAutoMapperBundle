<?php

namespace BCC\AutoMapperBundle\Mapper\FieldFilter;

/**
 * FieldFilterInterface applies a filter to a value after its resolution.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
interface FieldFilterInterface
{
    /**
     * Applies the filter to a given value.
     * 
     * @param $value mixed The value to filter
     * @return mixed The filtered value
     */
    function filter($value);
}
