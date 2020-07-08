<?php

namespace spec\PlanB\Edge\Application\UseCase;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Application\UseCase\DeleteCommand;
use PlanB\Edge\Application\UseCase\SaveCommand;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Domain\Entity\EntityInterface;

class DeleteCommandSpec extends ObjectBehavior
{
    public function let(EntityInterface $entity)
    {
        $this->beAnInstanceOf(ConcreteDeleteCommand::class);
        $this->beConstructedThrough('make', [$entity]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DeleteCommand::class);
    }

    public function it_return_entity(EntityInterface $entity)
    {
        $this->entity()->shouldBeEqualTo($entity);
    }

}

class ConcreteDeleteCommand extends DeleteCommand
{

}
