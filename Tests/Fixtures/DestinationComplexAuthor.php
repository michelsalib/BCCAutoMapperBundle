<?php


namespace BCC\AutoMapperBundle\Tests\Fixtures;


class DestinationComplexAuthor
{
    private $name;

    /**
     * DestinationComplexAuthor constructor.
     *
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }


}