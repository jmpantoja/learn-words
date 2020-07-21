<?php

namespace spec\PlanB\Edge\Domain\Validator\Exception;

use ArrayIterator;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationFailedExceptionSpec extends ObjectBehavior
{
    public function let(ConstraintViolationList $constraintViolationList,
                        ConstraintViolation $constraintViolation)
    {
        $constraintViolation->getPropertyPath()->willReturn('key');
        $constraintViolation->getMessage()->willReturn('message');

        $constraintViolationList->getIterator()->willReturn(new ArrayIterator([
            $constraintViolation->getWrappedObject()
        ]));

        $this->beConstructedWith($constraintViolationList);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ValidationFailedException::class);
    }

    public function it_returns_constraint_list(ConstraintViolationList $constraintViolationList)
    {
        $this->getViolationList()->shouldReturn($constraintViolationList);
    }

    public function it_returns_error_list(ConstraintViolationList $constraintViolationList)
    {
        $this->getViolationList()->shouldReturn($constraintViolationList);
        $this->getErrors()->shouldIterateAs([
            'key' => 'message'
        ]);
    }

}
