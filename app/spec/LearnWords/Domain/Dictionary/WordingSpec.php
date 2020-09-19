<?php

namespace spec\LearnWords\Domain\Dictionary;

use LearnWords\Domain\Dictionary\Wording;
use PhpSpec\ObjectBehavior;

class WordingSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('wording', 'description');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Wording::class);

        $this->getWording()->shouldReturn('wording');
        $this->getDescription()->shouldReturn('description');
    }

    public function it_removes_brackets_of_input_data()
    {
        $this->beConstructedWith('(wording)', '(description)');

        $this->getWording()->shouldReturn('wording');
        $this->getDescription()->shouldReturn('description');
    }

    public function it_validate_correctely()
    {
        $violations = $this::validate([
            'wording'=>'XX',
            'description'=>'XX',
        ]);

        $violations->count()->shouldReturn(2);
        $violations->get(0)->getMessage()->shouldReturn('This value is too short. It should have 3 characters or more.');
        $violations->get(1)->getMessage()->shouldReturn('This value is too short. It should have 4 characters or more.');
    }
}
