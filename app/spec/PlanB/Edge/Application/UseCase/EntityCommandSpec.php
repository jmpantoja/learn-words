<?php

namespace spec\PlanB\Edge\Application\UseCase;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Application\UseCase\EntityCommand;
use PlanB\Edge\Domain\Entity\EntityInterface;

class EntityCommandSpec extends ObjectBehavior
{
    public function let(EntityInterface $entity)
    {

        $this->beAnInstanceOf(ConcreteEntityCommand::class);
        $this->beConstructedWith($entity);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(EntityCommand::class);
    }

    public function it_returns_the_entity(EntityInterface $entity)
    {
        $this->getEntity()->shouldReturn($entity);
    }
}

class ConcreteEntityCommand extends EntityCommand
{
    public function __construct(EntityInterface $entity)
    {
        parent::__construct($entity);
    }
}
