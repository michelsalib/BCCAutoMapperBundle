<?php

namespace BCC\AutoMapperBundle\Mapper;

use Symfony\Component\Form\Util\PropertyPath;

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
     * {@inheritDoc}
     */
    public function getDestinationType()
    {
        return $this->destinationType;
    }

}
