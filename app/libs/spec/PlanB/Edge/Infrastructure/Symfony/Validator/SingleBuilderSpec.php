<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Validator;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderInterface;
use PlanB\Edge\Infrastructure\Symfony\Validator\SingleBuilder;
use Symfony\Component\Validator\Constraint;

class SingleBuilderSpec extends ObjectBehavior
{
    public function let(Constraint $constraint)
    {
        $this->beConstructedWith($constraint);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SingleBuilder::class);
        $this->shouldHaveType(ConstraintBuilderInterface::class);
    }

    public function it_is_able_to_set_a_constraint(Constraint $constraint)
    {
        $this->build()->shouldReturn([$constraint]);
    }

    public function it_is_able_to_set_two_constraints(Constraint $constraint, Constraint $otherConstraint)
    {
        $this->add($otherConstraint);

        $this->build()->shouldReturn([
            $constraint,
            $otherConstraint
        ]);
    }
}
