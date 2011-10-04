<?php

namespace BCC\AutoMapperBundle\Mapper\FieldAccessor;

use Symfony\Component\Form\Util\PropertyPath;
use Symfony\Component\Form\Exception\FormException;

/**
 * Simple returns a value for a member given a property path.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
class Simple implements FieldAccessorInterface
{

    private $sourcePropertyPath;

    /**
     * @param $sourcePropertyPath The property path
     */
    function __construct($sourcePropertyPath)
    {
        $this->sourcePropertyPath = new PropertyPath($sourcePropertyPath);
    }

    /**
     * {@inheritDoc}
     */
    public function getValue($source)
    {
        try {
            return $this->sourcePropertyPath->getValue($source);
        } catch (FormException $ex) {
            // ignore unfound properties
        }
    }

}
