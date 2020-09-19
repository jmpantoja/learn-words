<?php

namespace spec\LearnWords\Domain\Dictionary\EntryPersistence;


use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\EntryPersistence\EntryHasBeenUpdated;
use PhpSpec\ObjectBehavior;

class EntryHasBeenUpdatedSpec extends ObjectBehavior
{
    public function let(Entry $entry)
    {
        $this->beConstructedWith($entry);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(EntryHasBeenUpdated::class);
    }

    public function it_returns_the_word_object(Entry $entry)
    {
        $this->getEntry()->shouldReturn($entry);
    }
}
