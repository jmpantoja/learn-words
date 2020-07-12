<?php

namespace spec\PlanB\Edge\Domain\Validator;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Validator\CompositeConstraints;
use PlanB\Edge\Domain\Validator\ConstraintsFactory;
use PlanB\Edge\Domain\Validator\SingleConstraints;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Optional;
use Symfony\Component\Validator\Constraints\Required;

class ConstraintsFactorySpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(ConstraintsFactory::class);
    }

    public function it_is_able_to_create_a_composite_constraint_definition()
    {
        $this->composite()
            ->shouldBeAnInstanceOf(CompositeConstraints::class);
    }

    public function it_is_able_to_create_a_single_constraint_definition()
    {
        $this->single()
            ->shouldBeAnInstanceOf(SingleConstraints::class);
    }

    public function it_returns_constraints_from_composite_definition(Constraint $constraintA, Constraint $constraintB)
    {
        $this->composite()
            ->required('A', [$constraintA])
            ->optional('B', [$constraintB]);

        $fields = $this->constraints()[0]->getFromWrappedObject('fields');

        $fields['A']->shouldBeAnInstanceOf(Required::class);
        $fields['B']->shouldBeAnInstanceOf(Optional::class);
    }

    public function it_returns_constraints_from_single_definition(Constraint $constraintA, Constraint $constraintB)
    {
        $this->single()
            ->add($constraintA)
            ->add($constraintB);

        $this->constraints()
            ->shouldIterateLike([
                $constraintA,
                $constraintB
            ]);
    }
}
