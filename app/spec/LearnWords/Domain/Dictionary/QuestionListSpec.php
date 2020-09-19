<?php

namespace spec\LearnWords\Domain\Dictionary;

use LearnWords\Domain\Dictionary\Question;
use LearnWords\Domain\Dictionary\QuestionList;
use LearnWords\Domain\Dictionary\Tag;
use LearnWords\Domain\Dictionary\TagList;
use PhpSpec\ObjectBehavior;

class QuestionListSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedThrough('empty');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(QuestionList::class);
        $this->getType()->shouldReturn(Question::class);
    }


}
