<?php

namespace BCC\AutoMapperBundle\Mapper;

use Symfony\Component\Form\Util\PropertyPath;
use BCC\AutoMapperBundle\Mapper\FieldAccessor\SimpleFieldAccessor;
use BCC\AutoMapperBundle\Mapper\FieldAccessor\FieldAccessorInterface;

/**
 * AbstractMap returns a value for a member given a property path
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
abstract class AbstractMap implements MapInterface
{
    
    protected $fieldAccessors = array();

    /**
     * Associate a member to another member given their property pathes.
     * 
     * @param string $destinationMember
     * @param string $sourceMember
     * @return AbstractMap 
     */
    public function route($destinationMember, $sourceMember)
    {
        $this->fieldAccessors[$destinationMember] = new SimpleFieldAccessor($sourceMember);
        
        return $this;
    }
    
    /**
     * Applies a field accessor policy to a member.
     * 
     * @param string $destinationMember
     * @param FieldAccessorInterface $fieldMapper
     * @return AbstractMap 
     */
    public function forMember($destinationMember, FieldAccessorInterface $fieldMapper)
    {
        $this->fieldAccessors[$destinationMember] = $fieldMapper;
        
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFieldAccessors()
    {
        return $this->fieldAccessors;
    }

    /**
     * Builds the default map using property names.
     * 
     * @return AbstractMap 
     */
    public function buildDefaultMap()
    {
        $reflectionClass = new \ReflectionClass($this->getDestinationType());
        
        foreach ($reflectionClass->getProperties() as $property) {
            $this->fieldAccessors[$property->name] = new SimpleFieldAccessor($property->name);
        }
        
        return $this;
    }
}
