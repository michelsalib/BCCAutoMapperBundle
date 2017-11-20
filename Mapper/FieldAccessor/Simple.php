<?php

namespace BCC\AutoMapperBundle\Mapper\FieldAccessor;

use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyPath;

/**
 * Simple returns a value for a member given a property path.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
class Simple implements FieldAccessorInterface
{

    /**
     * @var PropertyPath
     */
    private $sourcePropertyPath;

    /**
     * @param string $sourcePropertyPath The property path
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
            return PropertyAccess::createPropertyAccessor()->getValue($source, $this->sourcePropertyPath);
        } catch (NoSuchPropertyException $ex) {
            // ignore properties not found
        }
    }
}
