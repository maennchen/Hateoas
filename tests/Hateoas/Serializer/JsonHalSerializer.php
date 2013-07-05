<?php

namespace tests\Hateoas\Serializer;

use tests\TestCase;
use Hateoas\Model\Link;
use Hateoas\Serializer\JsonHalSerializer as TestedJsonHalSerializer;

class JsonHalSerializer extends TestCase
{
    public function testSerializeLinks()
    {
        $jsonHalSerializer = new TestedJsonHalSerializer();

        $this->mockGenerator->orphanize('__construct');
        $jsonSerializationVisitor = new \mock\JMS\Serializer\JsonSerializationVisitor();

        $links = array(
            new Link('self', '/users/42', array('awesome' => 'exactly')),
            new Link('foo', '/bar'),
            new Link('foo', '/baz'),
            new Link('bar', '/foo'),
            new Link('bar', '/baz'),
            new Link('bar', '/buzz'),
        );

        $jsonHalSerializer->serializeLinks($links, $jsonSerializationVisitor);

        $expectedSerializedLinks = array(
            'self' => array(
                'href' => '/users/42',
                'awesome' => 'exactly',
            ),
            'foo' => array(
                array('href' => '/bar'),
                array('href' => '/baz'),
            ),
            'bar' => array(
                array('href' => '/foo'),
                array('href' => '/baz'),
                array('href' => '/buzz'),
            ),
        );

        $this
            ->mock($jsonSerializationVisitor)
                ->call('addData')
                    ->withArguments('_links', $expectedSerializedLinks)
                    ->once()
        ;
    }
}
