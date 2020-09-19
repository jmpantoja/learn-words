<?php

namespace spec\LearnWords\Domain\Dictionary\TagPersistence;

use LearnWords\Domain\Dictionary\Tag;
use LearnWords\Domain\Dictionary\TagPersistence\TagHasBeenCreated;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEvent;

class TagHasBeenCreatedSpec extends ObjectBehavior
{
    public function let(Tag $tag)
    {
        $this->beConstructedWith($tag);
    }

    public function it_is_initializable(Tag $tag)
    {
        $this->shouldHaveType(TagHasBeenCreated::class);
        $this->shouldHaveType(DomainEvent::class);

        $this->getTag()->shouldReturn($tag);
    }


}
