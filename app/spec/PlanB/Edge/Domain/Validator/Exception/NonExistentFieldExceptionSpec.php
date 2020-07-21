<?php

namespace spec\PlanB\Edge\Domain\Validator\Exception;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Validator\Exception\NonExistentFieldException;

class NonExistentFieldExceptionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('name', ['field1', 'field2']);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(NonExistentFieldException::class);
    }

    public function it_returns_a_valid_message(){
        $this->getMessage()->shouldReturn('El campo "name" no existe. (field1, field2)');
    }
}
