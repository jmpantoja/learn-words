<?php

namespace spec\LearnWords\Domain\Word\WordPersistence;

use LearnWords\Domain\Word\Word;
use LearnWords\Domain\Word\WordPersistence\WordHasBeenUpdated;
use PhpSpec\ObjectBehavior;

class WordHasBeenUpdatedSpec extends ObjectBehavior
{
    public function let(Word $word)
    {
        $this->beConstructedWith($word);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(WordHasBeenUpdated::class);
    }

    public function it_returns_the_word_object(Word $word)
    {
        $this->getWord()->shouldReturn($word);
    }
}
