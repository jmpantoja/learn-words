<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Constraints;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Constraints\Collection;
use PlanB\Edge\Infrastructure\Symfony\Constraints\CollectionValidator;
use Prophecy\Argument;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class CollectionValidatorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CollectionValidator::class);
    }

    public function it_throws_an_exception_when_constraint_is_not_appropriate(Constraint $constraint)
    {
        $this->shouldThrow(UnexpectedTypeException::class)->duringValidate(Argument::any(), $constraint);
    }

    public function it_ignores_validation_according_constraint()
    {
        $constraint = new ConcreteCollectionConstraint();
        $this->validate(Argument::any(), $constraint)->shouldReturn(null);
    }

    public function it_validate_data_according_constraint(ExecutionContextInterface $context)
    {

        $this->initialize($context);
        $constraint = new ConcreteCollectionConstraint();

        $violationList = $this->validate([
            'field' => 'bad'
        ], $constraint);

        $violationList->shouldReturn(null);
    }
}

class ConcreteCollectionConstraint extends Collection
{

    public function ignoreWhen($value): bool
    {
        return !is_array($value);
    }

    protected function getConstraints(): array
    {
        return [

        ];
    }
}
