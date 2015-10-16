<?php

namespace BCC\AutoMapperBundle\Mapper;

use BCC\AutoMapperBundle\Mapper\Exception\InvalidClassConstructorException;
use BCC\AutoMapperBundle\Mapper\FieldFilter\AbstractMappingFilter;
use Symfony\Component\PropertyAccess\PropertyAccess;

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
     *
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
     * @param string $destinationType
     *
     * @return MapInterface
     */
    public function getMap($sourceType, $destinationType)
    {
        if (!isset($this->maps[$sourceType])) {
            throw new \LogicException('There is no map that support this source type: '.$sourceType);
        }

        if (!isset($this->maps[$sourceType][$destinationType])) {
            throw new \LogicException('There is no map that support this destination type: '.$destinationType);
        }

        return $this->maps[$sourceType][$destinationType];
    }

    /**
     * Maps two object together, a map should exist.
     *
     * @param $source
     * @param $destination
     *
     * @return object
     * @throws InvalidClassConstructorException
     */
    public function map($source, $destination)
    {
        if (is_string($destination)) {
            $destinationRef = new \ReflectionClass($destination);

            if ($destinationRef->getConstructor() && $destinationRef->getConstructor()->getNumberOfRequiredParameters(
                ) > 0
            ) {
                throw new InvalidClassConstructorException($destination);
            }
            $destination = $destinationRef->newInstance();
        }

        $map = $this->getMap(
            $this->guessType($source),
            $this->guessType($destination)
        );
        $fieldAccessors = $map->getFieldAccessors();
        $fieldFilters = $map->getFieldFilters();

        foreach ($fieldAccessors as $path => $fieldAccessor) {
            $value = $fieldAccessor->getValue($source);

            if (isset($fieldFilters[$path])) {
                if (($filter = $fieldFilters[$path]) instanceof AbstractMappingFilter) {
                    $filter->setMapper($this);
                }

                $value = $filter->filter($value);
            }

            if ($map->getSkipNull() && is_null($value)) {
                continue;
            }

            $propertyAccessor = PropertyAccess::createPropertyAccessor();

            if ($map->getOverwriteIfSet()) {
                $propertyAccessor->setValue($destination, $path, $value);
            } else {
                if ($propertyAccessor->getValue($destination, $path) == null) {
                    $propertyAccessor->setValue($destination, $path, $value);
                }
            }
        }

        return $destination;
    }

    private function guessType($guessable)
    {
        return \is_array($guessable) ? 'array' : $this->filterClassName(\get_class($guessable));
    }

    private function filterClassname($className)
    {
        $result = $className;

        //because doctrine2 entity can be passed
        if ($pos = strpos($className, "Proxies\\__CG__\\") !== false) {
            //retrieve class namespace
            $result = mb_substr($className, strlen("Proxies\\__CG__\\"), strlen($className));
        }

        return $result;
    }
}
