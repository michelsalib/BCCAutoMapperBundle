<?php


namespace Mapper\FieldAccessor;


use BCC\AutoMapperBundle\Mapper\FieldAccessor\Expression;

class ExpressionTest extends \PHPUnit_Framework_TestCase
{
    public function testAccessObject()
    {
        $accessor = new Expression('field');

        $origin = new \stdClass();
        $origin->field = 'value';

        $this->assertEquals('value', $accessor->getValue($origin));
    }

    public function testAccessArray()
    {
        $accessor = new Expression('["friends"][0]["details"].name');

        $details = new \stdClass();
        $details->name = 'Josh';

        $user = array(
            'friends' => array(
                array(
                    'details' => $details
                )
            )
        );

        $this->assertEquals('Josh', $accessor->getValue($user));
    }

    /**
     * @expectedException \BCC\AutoMapperBundle\Mapper\Exception\InvalidSourceProperty
     */
    public function testAccessFail()
    {
        $accessor = new Expression('friends.details.name');

        $user = array(
            'friends' => array(
                array(
                    'details' => array(
                        'name' => 'Josh'
                    )
                )
            )
        );

        $this->assertEquals(null, $accessor->getValue($user));
    }

    public function testAccessString()
    {
        $accessor = new Expression('credentials.username');

        $user = 'user';

        $this->assertEquals(null, $accessor->getValue($user));
    }
}