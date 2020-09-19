<?php

namespace spec\PlanB\Edge\Domain\Validator\Exception;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Validator\Exception\ValidationException;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationExceptionSpec extends ObjectBehavior
{
    public function let(ConstraintViolationInterface $violationA, ConstraintViolationInterface $violationB)
    {
        $violationA->getPropertyPath()->willReturn('A');
        $violationA->getMessage()->willReturn('message_A');

        $violationB->getPropertyPath()->willReturn('B');
        $violationB->getMessage()->willReturn('message_B');

        $violationList = new ConstraintViolationList([
            $violationA->getWrappedObject(),
            $violationB->getWrappedObject(),
        ]);

        $this->beConstructedWith($violationList);

    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ValidationException::class);
    }

    public function it_returns_the_violation_list()
    {
        $violationList = $this->getConstraintViolationList();
        $violationList->shouldBeAnInstanceOf(ConstraintViolationList::class);
        $violationList->count()->shouldReturn(2);
    }

    public function it_returns_the_right_message()
    {
        $expected = <<<EOF
A: message_A
B: message_B
EOF;
        $this->getMessage()->shouldReturn($expected);
    }
}
