<?php

namespace spec\PlanB\Edge\Domain\Dto;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Dto\Dto;
use stdClass;

class DtoSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ConcreteDto::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Dto::class);
    }

    public function it_is_able_to_build_a_dto_from_an_object()
    {
        $object = new stdClass();
        $object->name = 'name';
        $object->lastName = 'lastName';

        $dto = $this::fromObject($object);

        $dto->name->shouldBeLike('name');
        $dto->lastName->shouldBeLike('lastName');
    }

    public function it_is_able_to_build_a_dto_from_an_array()
    {
        $dto = $this::fromArray([
            'name' => 'name',
            'lastName' => 'lastName'
        ]);

        $dto->name->shouldBeLike('name');
        $dto->lastName->shouldBeLike('lastName');
    }

    public function it_is_able_to_build_an_array_from_a_dto()
    {
        $dto = $this::fromArray([
            'name' => 'name',
            'lastName' => 'lastName'
        ]);

        $dto->toArray()->shouldReturn([
            'name' => 'name',
            'lastName' => 'lastName'
        ]);

    }

}

class ConcreteDto extends Dto
{
    public string $name;
    public string $lastName;
}
