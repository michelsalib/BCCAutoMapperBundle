<?php

namespace BCC\AutoMapperBundle\Mapper;

use Symfony\Component\Form\Util\PropertyPath;

/**
 * Mapper maps objects and manages maps.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
class Mapper
{

    private $maps = array();

    /**
     * Creates and registers a default map given the source and destination types.
     * 
     * @param string $sourceType
     * @param string $destinationMap
     * @return DefaultMap 
     */
    public function createMap($sourceType, $destinationMap)
    {
        return $this->maps[$sourceType][$destinationMap] = new DefaultMap($sourceType, $destinationMap);
    }
    
    /**
     * Registers a map to the mapper.
     * 
     * @param MapInterface $map 
     */
    public function registerMap(MapInterface $map)
    {
        $this->maps[$map->getSourceType()][$map->getDestinationType()] = $map;
    }

    /**
     * Obtains a registered map for the given source and destination types.
     * 
     * @param string $sourceType
     * @param string $destinationMap
     * @return Map 
     */
    public function getMap($sourceType, $destinationMap)
    {
        return $this->maps[$sourceType][$destinationMap];
    }

    /**
     * Maps two object together, a map should exist.
     * 
     * @param mixed $source
     * @param mixed $destination
     * @return mixed The destination object
     */
    public function map($source, $destination)
    {
        $map = $this->getMap(\get_class($source), \get_class($destination));
        $fieldAccessors = $map->getFieldAccessors();
        $fieldFilters = $map->getFieldFilters();
        
        foreach ($fieldAccessors as $path => $fieldAccessor) {
            $value = $fieldAccessor->getValue($source);
            
            if (isset($fieldFilters[$path])) {
                $value = $fieldFilters[$path]->filter($value);
            }
            
            $propertyPath = new PropertyPath($path);
            $propertyPath->setValue($destination, $value);
        }
        
        return $destination;
    }

}
