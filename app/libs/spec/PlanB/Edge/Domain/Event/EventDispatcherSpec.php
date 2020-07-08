<?php

namespace spec\PlanB\Edge\Domain\Event;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\EventDispatcher;

class EventDispatcherSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedThrough('getInstance');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(EventDispatcher::class);
    }

    public function it_is_a_singleton()
    {
        $instance = EventDispatcher::getInstance();
        $this->getInstance()->shouldReturn($instance);
    }


}
