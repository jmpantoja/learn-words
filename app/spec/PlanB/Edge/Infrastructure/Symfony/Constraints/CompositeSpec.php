<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Constraints;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Constraints\Composite;
use PlanB\Edge\Infrastructure\Symfony\Constraints\CompositeValidator;
use PlanB\Edge\Infrastructure\Validator\Constraints\Constraint;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;

class CompositeSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ConcreteComposite::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Composite::class);
    }

    public function it_throws_an_exception_if_option_composite_is_set()
    {
        $this->beConstructedWith([
            'constraints' => []
        ]);
        $this->shouldThrow(ConstraintDefinitionException::class)->duringInstantiation();
    }

    public function it_always_use_right_validator()
    {
        $this->validatedBy()->shouldReturn(CompositeValidator::class);
    }
}

class ConcreteComposite extends Composite
{

    /**
     * @inheritDoc
     */
    protected function getConstraints(array $options): array
    {
        return [];
    }
}
