<?php

namespace spec\PlanB\Edge\Domain\Validator\Constraints;

use PhpParser\Node\Arg;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Validator\Constraints\DataType;
use PlanB\Edge\Domain\Validator\Constraints\DataTypeValidator;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class DataTypeValidatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(DataTypeValidator::class);
    }

    public function it_is_able_to_validate_than_a_value_is_of_a_given_type(ExecutionContextInterface $context)
    {
        $constraint = new DataType([
            'type' => 'string'
        ]);

        $this->initialize($context);
        $this->validate('hola', $constraint);

        $context->buildViolation(Argument::any())
            ->shouldNotBeCalled();
    }

    public function it_is_able_to_validate_than_a_value_is_of_one_of_some_given_types(ExecutionContextInterface $context)
    {
        $constraint = new DataType([
            'type' => ['string', 'int']
        ]);

        $this->initialize($context);
        $this->validate(123, $constraint);

        $context->buildViolation(Argument::any())
            ->shouldNotBeCalled();
    }

    public function it_is_able_to_detect_an_invalid_value(ExecutionContextInterface $context,
                                                          ConstraintViolationBuilderInterface $violationBuilder)
    {
        $constraint = new DataType([
            'type' => ['string', 'int']
        ]);

        $context->buildViolation(Argument::any())->willReturn($violationBuilder);
        $violationBuilder->setParameter(Argument::any(), Argument::any())->willReturn($violationBuilder);
        $violationBuilder->setCode(Argument::any())->willReturn($violationBuilder);
        $violationBuilder->addViolation()->willReturn(null);

        $this->initialize($context);
        $this->validate(new stdClass(), $constraint);

        $context->buildViolation(Argument::any())
            ->shouldBeCalledOnce();
    }
}


