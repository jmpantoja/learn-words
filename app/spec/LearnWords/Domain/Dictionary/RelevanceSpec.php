<?php

namespace spec\LearnWords\Domain\Dictionary;

use LearnWords\Domain\Dictionary\Lang;
use LearnWords\Domain\Dictionary\Relevance;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Validator\Exception\ValidationException;

class RelevanceSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(10);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Relevance::class);
        $this->getRelevance()->shouldReturn(10);
    }

    public function it_throws_an_exception_when_input_data_is_wrong()
    {
        $this->beConstructedWith(-10);
        $this->shouldThrow(ValidationException::class)->duringInstantiation();
    }

    public function it_validate_correctely()
    {
        $violations = $this::validate(-10);

        $violations->count()->shouldReturn(1);
        $violations->get(0)->getMessage()->shouldReturn('This value should be between 1 and 10.');
    }
}
