<?php

namespace spec\PlanB\Edge\Domain\Validator;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Validator\ConstraintsDefinition;
use PlanB\Edge\Domain\Validator\SingleConstraints;
use Symfony\Component\Validator\Constraint;

class SingleConstraintsSpec extends ObjectBehavior
{
    public function let(Constraint $constraint)
    {
        $this->beConstructedWith($constraint);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SingleConstraints::class);
        $this->shouldHaveType(ConstraintsDefinition::class);
    }

    public function it_is_able_to_set_a_constraint(Constraint $constraint)
    {
        $this->getConstraints()->shouldReturn([$constraint]);
    }

    public function it_is_able_to_set_two_constraints(Constraint $constraint, Constraint $otherConstraint)
    {
        $this->add($otherConstraint);

        $this->getConstraints()->shouldReturn([
            $constraint,
            $otherConstraint
        ]);
    }
}
