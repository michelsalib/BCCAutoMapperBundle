<?php

namespace BCC\AutoMapperBundle\Tests\Mapper;

use BCC\AutoMapperBundle\Tests\Fixtures\DestinationPost;
use BCC\AutoMapperBundle\Tests\Fixtures\SourcePost;
use BCC\AutoMapperBundle\Tests\Fixtures\SourceAuthor;
use BCC\AutoMapperBundle\Tests\Fixtures\PrivateDestinationPost;
use BCC\AutoMapperBundle\Mapper\Mapper;
use BCC\AutoMapperBundle\Mapper\FieldAccessor\Closure;
use BCC\AutoMapperBundle\Tests\Fixtures\PostMap;
use BCC\AutoMapperBundle\Mapper\FieldFilter\IfNull;

/**
 * @author Michel Salib <michelsalib@hotmail.com>
 */
class MapperTest extends \PHPUnit_Framework_TestCase {

    public function testDefaultMap() {
        // ARRANGE
        $source = new SourcePost();
        $source->description = 'Symfony2 developer';
        $destination = new DestinationPost();
        $mapper = new Mapper();
        $mapper->createMap('BCC\AutoMapperBundle\Tests\Fixtures\SourcePost', 'BCC\AutoMapperBundle\Tests\Fixtures\DestinationPost');

        // ACT
        $mapper->map($source, $destination);

        // ASSERT
        $this->assertEquals('Symfony2 developer', $destination->description);
    }

    public function testCustomMap() {
        // ARRANGE
        $source = new SourcePost();
        $source->name = 'Michel';
        $source->description = 'Symfony2 developer';
        $destination = new DestinationPost();
        $mapper = new Mapper();
        $mapper->createMap('BCC\AutoMapperBundle\Tests\Fixtures\SourcePost', 'BCC\AutoMapperBundle\Tests\Fixtures\DestinationPost')
               ->route('title', 'name');

        // ACT
        $mapper->map($source, $destination);

        // ASSERT
        $this->assertEquals('Michel', $destination->title);
        $this->assertEquals('Symfony2 developer', $destination->description);
    }

    public function testInDepthMap() {
        // ARRANGE
        $source = new SourcePost();
        $source->author = new SourceAuthor();
        $source->author->name = 'Michel';
        $destination = new DestinationPost();
        $mapper = new Mapper();
        $mapper->createMap('BCC\AutoMapperBundle\Tests\Fixtures\SourcePost', 'BCC\AutoMapperBundle\Tests\Fixtures\DestinationPost')
               ->route('author', 'author.name');

        // ACT
        $mapper->map($source, $destination);

        // ASSERT
        $this->assertEquals('Michel', $destination->author);
    }

    public function testClosuredMap() {
        // ARRANGE
        $source = new SourcePost();
        $source->author = new SourceAuthor();
        $source->author->name = 'Michel';
        $destination = new DestinationPost();
        $mapper = new Mapper();
        $mapper->createMap('BCC\AutoMapperBundle\Tests\Fixtures\SourcePost', 'BCC\AutoMapperBundle\Tests\Fixtures\DestinationPost')
               ->forMember('author', new Closure(function(SourcePost $s){
                   return $s->author->name;
               }));

        // ACT
        $mapper->map($source, $destination);

        // ASSERT
        $this->assertEquals('Michel', $destination->author);
    }

    public function testMapRegistring(){
        // ARRANGE
        $source = new SourcePost();
        $source->name = 'Michel';
        $source->description = 'Symfony2 developer';
        $destination = new DestinationPost();
        $mapper = new Mapper();
        $mapper->registerMap(new PostMap());

        // ACT
        $mapper->map($source, $destination);

        // ASSERT
        $this->assertEquals('Michel', $destination->title);
        $this->assertEquals('Symfony2 developer', $destination->description);
    }

    public function testMappingPolicy() {
        // ARRANGE
        $source = new SourcePost();
        $source->name = 'Michel';
        $destination = new DestinationPost();
        $mapper = new Mapper();
        $mapper->createMap('BCC\AutoMapperBundle\Tests\Fixtures\SourcePost', 'BCC\AutoMapperBundle\Tests\Fixtures\DestinationPost')
               ->filter('title', new IfNull(''));

        // ACT
        $mapper->map($source, $destination);

        // ASSERT
        $this->assertEquals('', $destination->title);
    }

    public function testMappingArray() {
        // ARRANGE
        $source = array('title' => 'Michel');
        $destination = new DestinationPost();
        $mapper = new Mapper();
        $mapper->createMap('array', 'BCC\AutoMapperBundle\Tests\Fixtures\DestinationPost');

        // ACT
        $mapper->map($source, $destination);

        // ASSERT
        $this->assertEquals('Michel', $destination->title);
    }

    public function testIgnoreField() {
        // ARRANGE
        $source = new SourcePost();
        $source->description = 'Symfony2 developer';
        $destination = new PrivateDestinationPost();
        $mapper = new Mapper();
        $mapper->createMap('BCC\AutoMapperBundle\Tests\Fixtures\SourcePost', 'BCC\AutoMapperBundle\Tests\Fixtures\PrivateDestinationPost')
            ->ignoreMember('id');

        // ACT
        try {
            $mapper->map($source, $destination);
        } catch (\Exception $e) {
            $this->fail('should not catch an exception - ' . $e->getMessage());
        }

        // ASSERT
        $this->assertNull($destination->getId());
    }

    public function testOverwrittenIfSet() {
        $source = new SourcePost();
        $source->description = 'Symfony2 developer';
        $destination = new DestinationPost();
        $destination->description = 'Foo bar';
        $mapper = new Mapper();
        $mapper->createMap('BCC\AutoMapperBundle\Tests\Fixtures\SourcePost', 'BCC\AutoMapperBundle\Tests\Fixtures\DestinationPost');

        try {
            $mapper->map($source, $destination);
        } catch (\Exception $e) {
            $this->fail('should not catch an exception - ' . $e->getMessage());
        }

        $this->assertEquals('Symfony2 developer', $destination->description);
    }

    public function testNotOverwrittenIfSet() {
        $source = new SourcePost();
        $source->description = 'Symfony2 developer';
        $destination = new DestinationPost();
        $destination->description = 'Foo bar';
        $mapper = new Mapper();
        $mapper->createMap('BCC\AutoMapperBundle\Tests\Fixtures\SourcePost', 'BCC\AutoMapperBundle\Tests\Fixtures\DestinationPost')
            ->setOverwriteIfSet(false);

        try {
            $mapper->map($source, $destination);
        } catch (\Exception $e) {
            $this->fail('should not catch an exception - ' . $e->getMessage());
        }

        $this->assertEquals('Foo bar', $destination->description);
    }
    
    public function testSkipNull() {
        $source = new SourcePost();
        $source->description = null;
        $destination = new DestinationPost();
        $destination->description = 'Foo bar';
        $mapper = new Mapper();
        $mapper->createMap('BCC\AutoMapperBundle\Tests\Fixtures\SourcePost', 'BCC\AutoMapperBundle\Tests\Fixtures\DestinationPost')
               ->setSkipNull(true);

        try {
            $mapper->map($source, $destination);
        } catch (\Exception $e) {
            $this->fail('should not catch an exception - ' . $e->getMessage());
        }

        $this->assertEquals('Foo bar', $destination->description);
    }    
}
