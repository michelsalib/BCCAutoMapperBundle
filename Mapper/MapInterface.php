<?php

namespace BCC\AutoMapperBundle\Mapper;

/**
 * MapInterface defines a map interface.
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
interface MapInterface
{

    /**
     * @return array<Utils/FieldAccessorsInterface> An array of field accessors
     */
    public function getFieldAccessors();

    /**
     * @return string The source type
     */
    public function getSourceType();

    /**
     * @return string The destination type
     */
    public function getDestinationType();
}
