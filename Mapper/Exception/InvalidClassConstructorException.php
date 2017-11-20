<?php


namespace BCC\AutoMapperBundle\Mapper\Exception;


class InvalidClassConstructorException extends \Exception
{
    public function __construct($className, \Exception $previous = null)
    {
        parent::__construct(
            sprintf('Constructor for class "%s" is invalid. Should not have required arguments.', $className),
            0,
            $previous
        );
    }

}