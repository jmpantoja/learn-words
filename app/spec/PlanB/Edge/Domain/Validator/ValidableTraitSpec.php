<?php

namespace spec\PlanB\Edge\Domain\Validator;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Validator\Exception\ValidationException;
use PlanB\Edge\Domain\Validator\ValidableTrait;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidableTraitSpec extends ObjectBehavior
{
    const VALID = 'jojojo';
    const INVALID = 'jo';

    public function let()
    {
        $this->beAnInstanceOf(ConcreteValidable::class);
    }

    public function it_let_us_know_when_a_dataset_is_valid()
    {
        $this::isValid(self::VALID)->shouldReturn(true);
    }

    public function it_let_us_know_when_a_dataset_is_invalid()
    {
        $this::isValid(self::INVALID)->shouldReturn(false);
    }

    public function it_returns_an_empty_violations_list_when_input_is_valid()
    {
        $violationList = $this::validate(self::VALID);
        $violationList->shouldBeAnInstanceOf(ConstraintViolationList::class);
        $violationList->count()->shouldReturn(0);
    }

    public function it_returns_a_list_with_the_violations()
    {
        $violationList = $this::validate(self::INVALID);
        $violationList->shouldBeAnInstanceOf(ConstraintViolationList::class);
        $violationList->count()->shouldReturn(1);
    }

    public function it_throws_an_exception_when_ensure_an_invalid_input()
    {
        $this->shouldThrow(ValidationException::class)->duringEnsure(self::INVALID);
    }

    public function it_does_not_throws_an_exception_when_ensure_an_valid_input()
    {
        $this->shouldNotThrow(\Throwable::class)->duringEnsure(self::VALID);
    }
}

class ConcreteValidable
{
    use ValidableTrait;

    public static function getConstraints()
    {
        return new Length([
            'min' => 3
        ]);
    }
}
