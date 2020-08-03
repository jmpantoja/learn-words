<?php

namespace spec\PlanB\Edge\Domain\Entity;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Entity\Dto;
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

    public function it_is_initializable_by_default()
    {
        $response = $this::byDefault();

        $response->shouldHaveType(Dto::class);

        $response->toArray()->shouldReturn([
            'name' => 'nombre',
            'lastName' => 'apellidos',
        ]);
    }

    public function it_is_initializable_from_object()
    {

        $input = new stdClass();
        $input->name = 'pepe';
        $input->lastName = 'lopez';

        $response = $this::fromObject($input);

        $response->shouldHaveType(Dto::class);

        $response->toArray()->shouldReturn([
            'name' => 'pepe',
            'lastName' => 'lopez',
        ]);
    }

    public function it_is_initializable_from_array()
    {

        $response = $this::fromArray([
            'name' => 'pepe',
            'lastName' => 'lopez',
        ]);

        $response->shouldHaveType(Dto::class);

        $response->toArray()->shouldReturn([
            'name' => 'pepe',
            'lastName' => 'lopez',
        ]);
    }

    public function it_has_array_access()
    {
        $response = $this::byDefault();
        $response['name']->shouldReturn('nombre');

        $response['name'] = 'pepe';
        $response['name']->shouldReturn('pepe');

        unset($response['name']);
        $response['name']->shouldReturn(null);

    }

    public function it_is_able_to_update_an_existing_entity()
    {
        $entity = new stdClass();
        $this->process($entity)->shouldReturn($entity);
    }

    public function it_is_able_to_create_a_new_entity()
    {
        $entity = new stdClass();
        $this->process()->shouldNotReturn($entity);
        $this->process()->shouldBeLike($entity);
    }
}


class ConcreteDto extends Dto
{
    public ?string $name = 'nombre';
    public ?string $lastName = 'apellidos';


    public function update($entity): object
    {
        return $entity;
    }

    public function create(): object
    {
        return new stdClass();
    }
}
