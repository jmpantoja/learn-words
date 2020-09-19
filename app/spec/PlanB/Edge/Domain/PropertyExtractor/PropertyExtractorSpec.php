<?php

namespace spec\PlanB\Edge\Domain\PropertyExtractor;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\PropertyExtractor\PropertyExtractor;
use stdClass;

class PropertyExtractorSpec extends ObjectBehavior
{


    public function it_is_initializable()
    {
        $this->beConstructedThrough('fromObject', [new stdClass()]);
        $this->shouldHaveType(PropertyExtractor::class);
    }

    public function it_is_able_to_extract_properties()
    {
        $entity = new Dummy();
        $this->beConstructedThrough('fromObject', [$entity]);

        $this->toArray()->shouldReturn([
            'id' => null,
            'name' => 'pepe',
            'lastName' => 'lopez'
        ]);
    }

    public function it_is_able_to_extract_the_id()
    {
        $entity = new Dummy(40);
        $this->beConstructedThrough('fromObject', [$entity]);

        $this->id()->shouldReturn(40);
    }

    public function it_returns_null_when_id_is_not_initialized()
    {
        $entity = new Dummy();
        $this->beConstructedThrough('fromObject', [$entity]);

        $this->id()->shouldReturn(null);
    }

    public function it_returns_false_when_object_has_not_id()
    {
        $entity = new DummyWithOutId();
        $this->beConstructedThrough('fromObject', [$entity]);

        $this->hasIdentifier()->shouldReturn(false);
    }


    public function it_returns_null_when_there_is_not_id()
    {
        $entity = new stdClass();
        $this->beConstructedThrough('fromObject', [$entity]);

        $this->id()->shouldReturn(null);
    }
}

class Dummy
{
    private int $id;
    static int $age = 14;
    private string $name;
    private string $lastName;

    public function __construct($id = null)
    {
        $this->name = 'pepe';
        $this->lastName = 'lopez';

        if (null !== $id) {
            $this->id = $id;
        }
    }
}

class DummyWithOutId
{
    static int $age = 14;
    private string $name;
    private string $lastName;

    public function __construct()
    {
        $this->name = 'pepe';
        $this->lastName = 'lopez';
    }
}

