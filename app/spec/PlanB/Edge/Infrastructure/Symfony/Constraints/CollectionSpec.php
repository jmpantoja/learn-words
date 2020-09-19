<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Constraints;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Constraints\Collection;
use PlanB\Edge\Infrastructure\Symfony\Constraints\CollectionValidator;

class CollectionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ConcreteConsraint::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Collection::class);
    }

    public function it_allways_uses_same_validator()
    {
        $this->validatedBy()->shouldReturn(CollectionValidator::class);
    }
}

class ConcreteConsraint extends Collection
{

    public function ignoreWhen($value): bool
    {
        return false;
    }

    protected function getConstraints(): array
    {
        return array();
    }
}
