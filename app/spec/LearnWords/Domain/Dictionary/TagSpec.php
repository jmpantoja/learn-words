<?php

namespace spec\LearnWords\Domain\Dictionary;

use LearnWords\Domain\Dictionary\Tag;
use LearnWords\Domain\Dictionary\TagId;
use PhpSpec\ObjectBehavior;

class TagSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('tag_name');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Tag::class);
        $this->getTag()->shouldReturn('tag_name');
        $this->getId()->shouldBeAnInstanceOf(TagId::class);
    }


    public function it_is_updatable()
    {
        $this->update('new value');
        $this->getTag()->shouldReturn('new value');
    }

}
