<?php

namespace BCC\AutoMapperBundle\Tests\Fixtures;

use BCC\AutoMapperBundle\Mapper\AbstractMap;

/**
 * @author Michel Salib <michelsalib@hotmail.com>
 */
class PostMap extends AbstractMap {

    function __construct() {
        $this->buildDefaultMap();
        $this->route('title', 'name');
    }

    public function getDestinationType() {
        return 'BCC\AutoMapperBundle\Tests\Fixtures\DestinationPost';
    }

    public function getSourceType() {
        return 'BCC\AutoMapperBundle\Tests\Fixtures\SourcePost';
    }

}
