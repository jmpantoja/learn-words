<?php

namespace spec\LearnWords\Domain\Dictionary;

use LearnWords\Domain\Dictionary\Tag;
use LearnWords\Domain\Dictionary\TagList;
use PhpSpec\ObjectBehavior;

class TagListSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedThrough('empty');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TagList::class);
        $this->getType()->shouldReturn(Tag::class);
    }


}
