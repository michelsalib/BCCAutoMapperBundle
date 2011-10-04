<?php

namespace BCC\AutoMapperBundle\Mapper\FieldAccessor;

use Symfony\Component\Form\FieldAccessor\PropertyPath;
use Symfony\Component\Form\Exception\FormException;

/**
 * Closure access a member value using a closure.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
class Closure implements FieldAccessorInterface
{

    /**
     * @var \Closure 
     */
    private $closure;

    /**
     * @param $closure The closure
     */
    function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * {@inheritDoc}
     */
    public function getValue($source)
    {
        $closure = $this->closure;

        return $closure($source);
    }

}
