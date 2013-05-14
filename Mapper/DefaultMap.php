<?php

namespace BCC\AutoMapperBundle\Mapper;

use Symfony\Component\Form\Util\PropertyPath;
use  BCC\AutoMapperBundle\Mapper\FieldAccessor\Simple;

/**
 * DefaultMap is an auto genrated map.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
class DefaultMap extends AbstractMap
{

    private $sourceType;
    private $destinationType;

    /**
     * Creates a default map given the source and destination types.
     *
     * @param string $sourceType
     * @param string $destinationMap
     */
    function __construct($sourceType, $destinationMap)
    {
        $this->sourceType = $sourceType;
        $this->destinationType = $destinationMap;

        $this->buildDefaultMap();
    }

    /**
     * {@inheritDoc}
     */
    public function getSourceType()
    {
        return $this->fieldAccessors;
    }

    /**
     * Associate a member to another member given their property pathes.
     *
     * @param string $destinationMember
     * @param string $sourceMember
     * @return AbstractMap
     */
    public function route($destinationMember, $sourceMember)
    {
        $this->fieldAccessors[$destinationMember] = new Simple($this->getCorrectPropertyPath($sourceMember));

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDestinationType()
    {
        return $this->destinationType;
    }

    public function buildDefaultMap()
    {
        $reflectionClass = new \ReflectionClass($this->getDestinationType());

        foreach ($reflectionClass->getProperties() as $property) {
            $this->fieldAccessors[$property->name] = new Simple($this->getCorrectPropertyPath($property->name));
        }

        return $this;
    }

    private function getCorrectPropertyPath($name)
    {
        return $this->sourceType == 'array' ? '['.$name.']' : $name;
    }

}
