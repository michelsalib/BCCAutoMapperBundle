# Intro to AutoMapper

Inspired by http://github.com/AutoMapper/AutoMapper, this Bundle provides a object to object mapper.

[![Build Status](https://secure.travis-ci.org/michelsalib/BCCAutoMapperBundle.png?branch=master)](http://travis-ci.org/michelsalib/BCCAutoMapperBundle)

## Installation and configuration:

### Get the bundle

Add to your `/deps` file :

```
[BCCAutoMapperBundle]
    git=http://github.com/michelsalib/BCCAutoMapperBundle.git
    target=/bundles/BCC/AutoMapperBundle
```

And make a `php bin/vendors install`.

### Register the namespace

``` php
<?php

    // app/autoload.php
    $loader->registerNamespaces(array(
        'BCC' => __DIR__.'/../vendor/bundles',
        // your other namespaces
    ));
```

### Add BCCAutoMapperBundle to your application kernel

``` php
<?php

    // app/AppKernel.php
    public function registerBundles()
    {
        return array(
            // ...
            new BCC\AutoMapperBundle\BCCAutoMapperBundle(),
            // ...
        );
    }
```

## Usage examples:

Giving a source and a destination object:

``` php
<?php

namespace My;

class SourcePost {

    public $name;
    public $description;
    /**
     * @var SourceAuthor
     */
    public $author;

}

class SourceAuthor {

    public $name;

}

class DestinationPost {

    public $title;
    public $description;
    public $author;

}
```

### Use default map :

THe default map will automatically associate members that have the same name. It will automatically use public properties or look for getters.

You can create a default map and map object this way:

``` php
<?php

// get mapper
$mapper = $container->get('bcc_auto_mapper.mapper');
// create default map
$mapper->createMap('My\SourcePost', 'My\DestinationPost');

// create objects
$source = new SourcePost();
$source->description = 'Symfony2 developer';
$destination = new DestinationPost();

// map
$mapper->map($source, $destination);

echo $destination->description; // outputs 'Symfony2 developer'
```

### Route members

On the previous example the fields `name` and `title` did not match. You can route members this way:

``` php
<?php

// get mapper
$mapper = $container->get('bcc_auto_mapper.mapper');
// create default map and route members
$mapper->createMap('My\SourcePost', 'My\DestinationPost')
       ->route('title', 'name');

// create objects
$source = new SourcePost();
$source->name = 'AutoMapper Bundle';
$destination = new DestinationPost();

// map
$mapper->map($source, $destination);

echo $destination->title; // outputs 'AutoMapper Bundle'
```

Note that if `title` or `name` is private, it will try to use getter and setter to route the member.

### Map member with a closure

If you need some extra computation when mapping a member, you can provide a closure that will handle a specific member:

``` php
<?php

use BCC\AutoMapperBundle\Mapper\FieldAccessor\Closure;

// get mapper
$mapper = $container->get('bcc_auto_mapper.mapper');
// create default map and override members
$mapper->createMap('My\SourcePost', 'My\DestinationPost')
       ->forMember('title', new Closure(function(SourcePost $source){
           return \strtoupper($source->name);
       }));

// create objects
$source = new SourcePost();
$source->name = 'AutoMapper Bundle';
$destination = new DestinationPost();

// map
$mapper->map($source, $destination);

echo $destination->title; // outputs 'AUTOMAPPER BUNDLE'
```

### Map a graph

You can map the author->name member this way:

``` php
<?php

// get mapper
$mapper = $container->get('bcc_auto_mapper.mapper');
// create default map and override members
$mapper->createMap('My\SourcePost', 'My\DestinationPost')
       ->route('author', 'author.name');

// create objects
$source = new SourcePost();
$source->author = new SourceAuthor();
$source->author->name = 'Michel';
$destination = new DestinationPost();
$mapper = new Mapper();

// map
$mapper->map($source, $destination);

echo $destination->author; // outputs 'Michel'
```

Note that if there are private members, it will try to use getter and setter to route the member.

#### Use Symfony Expression Language

If you want to define how properties are accessed, use **Expression** field accessor:
You can read all [documentation about ExpressionLanguage](http://symfony.com/doc/current/components/expression_language/index.html).

``` php
<?php

// get mapper
$mapper = $container->get('bcc_auto_mapper.mapper');
// create default map and override members
$mapper->createMap('My\SourcePost', 'My\DestinationPost')
       ->forMember('author', new Expression('author.getFullName()'));

// create objects
$source = new SourcePost();
$source->author = new SourceAuthor();
$source->author->name = 'Michel';
$destination = new DestinationPost();
$mapper = new Mapper();

// map
$mapper->map($source, $destination);

echo destination->author; // outputs 'Michel'
```

### Map to a constant

You can map a specific member to a constant:

``` php
<?php

use BCC\AutoMapperBundle\Mapper\FieldAccessor\Constant;

// get mapper
$mapper = $container->get('bcc_auto_mapper.mapper');
// create default map and override members
$mapper->createMap('My\SourcePost', 'My\DestinationPost')
       ->forMember('title', new Constant('Constant title'));

// create objects
$source = new SourcePost();
$source->name = 'AutoMapper Bundle';
$destination = new DestinationPost();

// map
$mapper->map($source, $destination);

echo $destination->title; // outputs 'Constant title'
```

### Deep object mapping

You can map a specific member to a constant:

``` php
<?php

use BCC\AutoMapperBundle\Mapper\FieldAccessor\Constant;

// get mapper
$mapper = $container->get('bcc_auto_mapper.mapper');
// create default map
$mapper->createMap('My\SourcePost', 'My\DestinationPost');
// Set property deep mapping
$mapper->filter('author', new ObjectMappingFilter('My\DestinationAuthor'));

// create objects
$source = new SourcePost();
$source->author = new SourceAuthor();
$destination = new DestinationPost();

// map
$mapper->map($source, $destination);

echo get_class(destination->author); // outputs 'My\DestinationAuthor'
```

### Deep array object mapping

You can map a specific member to a constant:

``` php
<?php

use BCC\AutoMapperBundle\Mapper\FieldAccessor\Constant;

// get mapper
$mapper = $container->get('bcc_auto_mapper.mapper');
// create default map
$mapper->createMap('My\SourcePost', 'My\DestinationPost');
// Set property deep mapping
$mapper->filter('comments', new ArrayObjectMappingFilter('My\DestinationComment'));

// create objects
$source = new SourcePost();
$source->comments = array(
    new SourceComment(),
    new SourceComment(),
);
$destination = new DestinationPost();

// map
$mapper->map($source, $destination);

echo get_class(destination->comments[0]); // outputs 'My\DestinationComment'
```

### Apply a filter

You can apply a filter to a mapped member. Right now there is just a `IfNull` filter that applies a default value if the field could not be mapped or is mapped on a null value:

``` php
<?php

use BCC\AutoMapperBundle\Mapper\FieldAccessor\IfNull;

// get mapper
$mapper = $container->get('bcc_auto_mapper.mapper');
// create default map and override members
$mapper->createMap('My\SourcePost', 'My\DestinationPost')
       ->filter('title', new IfNull('Default title'));

// create objects
$source = new SourcePost();
$source->name = 'AutoMapper Bundle';
$destination = new DestinationPost();

// map
$mapper->map($source, $destination);

echo $destination->title; // outputs 'Default title'
```

## Register a map

You can define map and add them to the Mapper at the container level.

Extend the `BCC\AutoMapperBundle\Mapper\AbstractMap` class:

``` php
<?php

namespace My;

use BCC\AutoMapperBundle\Mapper\AbstractMap;

class PostMap extends AbstractMap {

    function __construct() {
        $this->buildDefaultMap(); // generate the default map
        $this->route('title', 'name'); // override the title member
    }

    public function getDestinationType() {
        return 'My\DestinationPost';
    }

    public function getSourceType() {
        return 'My\SourcePost';
    }

}
```

Don't forget to declare it as a service with the `bcc_auto_mapper.map` tag:

``` xml
<service id="my.map" class="My\PostMap">
    <tag name="bcc_auto_mapper.map" />
</service>
```

You can now use the mapper directly:

``` php
<?php

// get mapper
$mapper = $container->get('bcc_auto_mapper.mapper');

// create objects
$source = new SourcePost();
$source->name = 'AutoMapper Bundle';
$destination = new DestinationPost();

// map
$mapper->map($source, $destination);

echo $destination->title; // outputs 'AutoMapper Bundle'
```

## Ignore a field

You can ignore a destination field.

``` php
<?php

// get mapper
$mapper = $container->get('bcc_auto_mapper.mapper');
// create default map
$mapper->createMap('My\SourcePost', 'My\DestinationPost')
    ->ignoreMember('description');

// create objects
$source = new SourcePost();
$source->description = 'Symfony2 developer';
$destination = new DestinationPost();

// map
$mapper->map($source, $destination);

var_dump($destination->description); // ignored, will be null
```

## Do not overwrite already set field

You can have the mapper not overwrite a field that is set on the destination.

``` php
<?php

// get mapper
$mapper = $container->get('bcc_auto_mapper.mapper');
// create default map
$mapper->createMap('My\SourcePost', 'My\DestinationPost')
    ->setOverwriteIfSet(false);

// create objects
$source = new SourcePost();
$source->description = 'Symfony2 developer';
$destination = new DestinationPost();
$destination->description = 'Foo bar';

// map
$mapper->map($source, $destination);

var_dump($destination->description); // will be 'Foo bar'
```

## Skip null

You can skip a field that is null.

``` php
<?php

// get mapper
$mapper = $container->get('bcc_auto_mapper.mapper');
// create default map
$mapper->createMap('My\SourcePost', 'My\DestinationPost')
       ->setSkipNull(true);

// create objects
$source = new SourcePost();
$source->description = null;
$destination = new DestinationPost();
$destination->description = 'Foo bar';

// map
$mapper->map($source, $destination);

var_dump($destination->description); // will be 'Foo bar'
```
