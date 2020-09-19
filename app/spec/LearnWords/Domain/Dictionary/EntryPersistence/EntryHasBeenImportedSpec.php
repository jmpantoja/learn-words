<?php

namespace spec\LearnWords\Domain\Dictionary\EntryPersistence;

use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\EntryPersistence\EntryHasBeenImported;
use PhpSpec\ObjectBehavior;

class EntryHasBeenImportedSpec extends ObjectBehavior
{
    public function let(Entry  $entry){
        $this->beConstructedWith($entry);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(EntryHasBeenImported::class);
    }

    public function it_returns_the_entry_object(Entry $entry)
    {
        $this->getEntry()->shouldReturn($entry);
    }
}
