<?php

namespace BCC\AutoMapperBundle\Mapper\FieldAccessor;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyPath;

/**
 * Simple returns a value for a member given a property path.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
class Expression implements FieldAccessorInterface
{

    /**
     * @var PropertyPath
     */
    private $sourcePropertyPath;

    /**
     * @param string|\Symfony\Component\ExpressionLanguage\Expression $sourcePropertyPath The property path
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
        $expLanguage = new ExpressionLanguage();
        try {
            return $expLanguage->evaluate($this->sourcePropertyPath, $source);
        } catch (NoSuchPropertyException $ex) {
            // ignore properties not found
        }
    }

}
