<?php

namespace BCC\AutoMapperBundle\Mapper\FieldAccessor;

use Symfony\Component\Form\Util\PropertyPath;
use Symfony\Component\Form\Exception\FormException;

/**
 * Constant returns a constant as a value for a member.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
class Constant implements FieldAccessorInterface
{

    /**
     * @var mixed 
     */
    private $value;

    /**
     * @param $value The constant
     */
    function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue($source)
    {
        return $this->value;
    }

}
