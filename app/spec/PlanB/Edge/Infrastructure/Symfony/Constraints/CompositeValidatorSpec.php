<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Constraints;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Constraints\Composite;
use PlanB\Edge\Infrastructure\Symfony\Constraints\CompositeValidator;
use stdClass;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CompositeValidatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CompositeValidator::class);
    }

    public function it_is_able_to_validate_a_value(Composite $composite,
                                                   ExecutionContextInterface $context,
                                                   ValidatorInterface $validator)
    {

        $data = new stdClass();
        $context->getValidator()->willReturn($validator);
        $constraints = [];

        $composite->constraints = $constraints;

        $validator->inContext($context)->willReturn($validator);

        $this->initialize($context);
        $this->validate($data, $composite);

        $validator->validate($data, $constraints)->shouldHaveBeenCalled();
    }

    public function it_throws_an_exception_when_constraint_is_not_of_the_right_type(Constraint $constraint)
    {
        $data = new stdClass();
        $this->shouldThrow(UnexpectedTypeException::class)->duringValidate($data, $constraint);
    }
}
