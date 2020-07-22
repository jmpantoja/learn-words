<?php

namespace spec\PlanB\Edge\Domain\Validator\Traits;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Validator\ConstraintsFactory;
use PlanB\Edge\Domain\Validator\Exception\ValidationFailedException;
use PlanB\Edge\Domain\Validator\Traits\ValidableTrait;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidableTraitSpec extends ObjectBehavior
{
    const VALID_INPUT = 'ssssss';
    const WRONG_INPUT = 'ss';

    public function let()
    {
        $this->beAnInstanceOf(ConcreteValidable::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ConcreteValidable::class);

    }

    public function it_detects_a_valid_value()
    {
        $this::isValid(self::VALID_INPUT)->shouldReturn(true);
    }

    public function it_detects_an_invalid_value()
    {
        $this::isValid(self::WRONG_INPUT)->shouldReturn(false);
    }

    public function it_throws_an_exception_when_input_data_is_wrong()
    {
        $this->shouldThrow(ValidationFailedException::class)
            ->during('ensureIsValid', [self::WRONG_INPUT]);
    }

    public function it_does_not_throws_an_exception_when_input_data_is_right()
    {
        $this->shouldNotThrow(ValidationFailedException::class)
            ->during('ensureIsValid', [self::VALID_INPUT]);
    }



    public function it_returns_a_list_with_the_constraints()
    {
        $constraints = $this::getConstraints();

        $constraints->shouldHaveCount(1);
        $constraints[0]->shouldBeAnInstanceOf(Length::class);
    }

    public function it_returns_a_list_with_the_violations()
    {
        $violations = $this::validate(self::WRONG_INPUT);

        $violations->shouldBeAnInstanceOf(ConstraintViolationList::class);
        $violations->shouldHaveCount(1);
        $violations[0]->shouldBeAnInstanceOf(ConstraintViolation::class);
    }
}

class ConcreteValidable
{
    use ValidableTrait;

    public static function configureValidator(ConstraintsFactory $factory): void
    {
        $factory->single()
            ->add(new Length([
                'min' => 3
            ]));
    }
}
