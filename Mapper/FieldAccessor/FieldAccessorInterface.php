<?php

namespace BCC\AutoMapperBundle\Mapper\FieldAccessor;

use Symfony\Component\Form\Util\PropertyPath;

/**
 * FieldAccessorInterface defines how the value of a member is resolved.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
interface FieldAccessorInterface
{

    /**
     * Gets the value for the member given the source object.
     * 
     * @param mixed $source The source object
     * @return mixed The value
     */
    function getValue($source);
}
