<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Normalizer\Exception;

use ArrayIterator;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Normalizer\Exception\ValidationInputException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationInputExceptionSpec extends ObjectBehavior
{
    public function let(ConstraintViolationList $violationList, ConstraintViolation $first, ConstraintViolation $second)
    {
        $first->getPropertyPath()->willReturn('A');
        $first->getMessage()->willReturn('error A');

        $second->getPropertyPath()->willReturn('B');
        $second->getMessage()->willReturn('error B');


        $violationList->getIterator()->willReturn(new ArrayIterator([
            $first->getWrappedObject(),
            $second->getWrappedObject()
        ]));

        $this->beConstructedWith($violationList);

    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ValidationInputException::class);
    }

    public function it_returns_the_valid_message()
    {

        $this->getMessage()->shouldReturn("A: error A\nB: error B");
    }
}
