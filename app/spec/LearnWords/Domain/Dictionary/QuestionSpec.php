<?php

namespace spec\LearnWords\Domain\Dictionary;

use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\Example;
use LearnWords\Domain\Dictionary\Question;
use LearnWords\Domain\Dictionary\Wording;
use PhpSpec\ObjectBehavior;

class QuestionSpec extends ObjectBehavior
{
    public function let(Entry $entry, Wording $wording, Example $example)
    {
        $this->beConstructedWith($entry, $wording, $example);
    }

    public function it_is_initializable(Wording $wording, Example $example)
    {
        $this->shouldHaveType(Question::class);

        $this->getWording()->shouldReturn($wording);
        $this->getExample()->shouldReturn($example);
    }


    public function it_is_updatable(Wording $otherWording, Example $otherExample)
    {
        $this->update($otherWording, $otherExample);

        $this->getWording()->shouldReturn($otherWording);
        $this->getExample()->shouldReturn($otherExample);
    }


}
