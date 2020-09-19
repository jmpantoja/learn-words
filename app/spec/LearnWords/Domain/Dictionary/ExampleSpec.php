<?php

namespace spec\LearnWords\Domain\Dictionary;

use LearnWords\Domain\Dictionary\Example;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Validator\Exception\ValidationException;

class ExampleSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('sample', 'translation');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Example::class);

        $this->getSample()->shouldReturn('sample');
        $this->getTranslation()->shouldReturn('translation');
    }

    public function it_throws_an_exception_when_input_data_is_wrong()
    {
        $this->beConstructedWith('XX', 'XX');
        $this->shouldThrow(ValidationException::class)->duringInstantiation();
    }

    public function it_validate_correctely()
    {
        $violations = $this::validate([
            'sample'=>'XX',
            'translation'=>'XX',
        ]);

        $violations->count()->shouldReturn(2);
        $violations->get(0)->getMessage()->shouldReturn('This value is too short. It should have 4 characters or more.');
        $violations->get(1)->getMessage()->shouldReturn('This value is too short. It should have 4 characters or more.');
    }
}
