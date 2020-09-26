<?php

namespace spec\LearnWords\Domain\Dictionary;

use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\Example;
use LearnWords\Domain\Dictionary\Question;
use LearnWords\Domain\Dictionary\Relevance;
use LearnWords\Domain\Dictionary\Wording;
use PhpSpec\ObjectBehavior;

class QuestionSpec extends ObjectBehavior
{
    public function let(Entry $entry, Wording $wording, Example $example, Relevance $relevance)
    {
        $this->beConstructedWith($entry, $wording, $example, $relevance);
    }

    public function it_is_initializable(Entry $entry, Wording $wording, Example $example, Relevance $relevance)
    {
        $this->shouldHaveType(Question::class);

        $this->getWording()->shouldReturn($wording);
        $this->getExample()->shouldReturn($example);
        $this->getRelevance()->shouldReturn($relevance);
        $this->getEntry()->shouldReturn($entry);
    }


    public function it_is_updatable(Wording $otherWording, Example $otherExample, Relevance $otherRelevance)
    {
        $this->update($otherWording, $otherExample, $otherRelevance);

        $this->getWording()->shouldReturn($otherWording);
        $this->getExample()->shouldReturn($otherExample);
        $this->getRelevance()->shouldReturn($otherRelevance);
    }
}
