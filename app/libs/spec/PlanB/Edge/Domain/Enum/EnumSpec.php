<?php

namespace spec\PlanB\Edge\Domain\Enum;

use LogicException;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Enum\Enum;

class EnumSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ConcreteEnum::class);
        $this->beConstructedThrough('make', ['KEYA']);
    }

    public function it_is_initializable_using_make_method_and_a_key()
    {
        $this->beConstructedThrough('make', ['KEYA']);
        $this->getKey()->shouldReturn('KEYA');
    }

    public function it_is_initializable_by_key()
    {
        $this->beConstructedThrough('byKey', ['KEYA']);
        $this->getKey()->shouldReturn('KEYA');
    }

    public function it_is_initializable_using_make_method_and_a_value()
    {
        $this->beConstructedThrough('make', ['ValueA']);
        $this->getKey()->shouldReturn('KEYA');
    }

    public function it_is_initializable_by_value()
    {
        $this->beConstructedThrough('byValue', ['ValueA']);
        $this->getKey()->shouldReturn('KEYA');
    }


    public function it_is_initializable_using_make_method_and_an_other_instance()
    {
        $other = ConcreteEnum::KEYA();

        $this->beConstructedThrough('make', [$other]);
        $this->is($other)->shouldReturn(true);
    }

    public function it_throws_an_exception_when_try_to_instantiate_with_an_invalid_value()
    {

        $this->beConstructedThrough('make', ['xxxx']);

        $this->shouldThrow(LogicException::class)->duringInstantiation();
    }

    public function it_determine_if_a_key_is_valid()
    {
        $this::hasKey('KEYA')->shouldReturn(true);
        $this::hasKey('KEYB')->shouldReturn(true);
        $this::hasKey('XXXX')->shouldReturn(false);
    }

    public function it_determine_if_a_value_is_valid()
    {
        $this::hasValue('ValueA')->shouldReturn(true);
        $this::hasValue('ValueB')->shouldReturn(true);
        $this::hasValue('XXXX')->shouldReturn(false);
    }

    public function it_determine_if_is_equals_than_other()
    {
        $otherA = ConcreteEnum::KEYA();
        $this->is($otherA)->shouldReturn(true);

        $otherB = ConcreteEnum::KEYB();
        $this->is($otherB)->shouldReturn(false);
    }

    public function it_is_able_to_converts_to_string(){
        $this->__toString()->shouldReturn('KEYA');
    }
}

class ConcreteEnum extends Enum
{
    private const KEYA = 'ValueA';
    private const KEYB = 'ValueB';
}
