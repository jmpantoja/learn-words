<?php

namespace spec\PlanB\Edge\Domain\Validator\Constraints;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Validator\Constraints\DataType;
use PlanB\Edge\Domain\Validator\Constraints\DataTypeValidator;
use Prophecy\Argument;
use stdClass;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class DataTypeValidatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(DataTypeValidator::class);
    }

    public function it_throws_an_exception_when_is_invoked_with_another_constraint(Constraint $constraint)
    {
        $this->shouldThrow(UnexpectedTypeException::class)
            ->during('validate', ['value', $constraint]);
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

        $this->prepareContext($context, $violationBuilder);

        $this->initialize($context);
        $this->validate(new stdClass(), $constraint);

        $context->buildViolation(Argument::any())
            ->shouldBeCalledOnce();
    }

    public function it_is_able_to_detect_null_like_invalid_value_when_it_is_not_allowed(ExecutionContextInterface $context,
                                                                                        ConstraintViolationBuilderInterface $violationBuilder)
    {
        $constraint = new DataType([
            'type' => ['int'],
            'allowNull' => false
        ]);

        $this->prepareContext($context, $violationBuilder);

        $this->initialize($context);
        $this->validate(null, $constraint);

        $context->buildViolation(Argument::any())
            ->shouldBeCalledOnce();
    }

    public function it_is_able_to_detect_null_like_valid_value_when_it_is_allowed(ExecutionContextInterface $context,
                                                                                        ConstraintViolationBuilderInterface $violationBuilder)
    {
        $constraint = new DataType([
            'type' => ['int'],
            'allowNull' => true
        ]);

        $this->prepareContext($context, $violationBuilder);

        $this->initialize($context);
        $this->validate(null, $constraint);

        $context->buildViolation(Argument::any())
            ->shouldNotBeCalled();
    }

    /**
     * @param $context
     * @param $violationBuilder
     */
    private function prepareContext($context, $violationBuilder): void
    {
        $context->buildViolation(Argument::any())->willReturn($violationBuilder);
        $violationBuilder->setParameter(Argument::any(), Argument::any())->willReturn($violationBuilder);
        $violationBuilder->setCode(Argument::any())->willReturn($violationBuilder);
        $violationBuilder->addViolation()->willReturn(null);
    }
}


