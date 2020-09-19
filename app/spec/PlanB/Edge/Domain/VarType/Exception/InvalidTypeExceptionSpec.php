<?php

namespace spec\PlanB\Edge\Domain\VarType\Exception;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\VarType\Exception\InvalidTypeException;
use stdClass;

class InvalidTypeExceptionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(new stdClass(), 'string');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(InvalidTypeException::class);
    }

    public function it_returns_the_right_message(){
        $this->getMessage()->shouldReturn('Se esperaba un argumento de tipo "string", pero se ha pasado un "stdClass"');
    }
}
