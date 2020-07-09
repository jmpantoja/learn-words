<?php

namespace spec\PlanB\Edge\Domain\Event;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEventInterface;
use PlanB\Edge\Domain\Event\DomainEventsCollector;

class DomainEventsCollectorSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(DomainEventsCollector::class);
    }

    public function it_collects_domain_events(DomainEventInterface $event){

        $this->events()->shouldHaveCount(0);

        $this->handle($event);
        $this->events()->shouldHaveCount(1);

        $this->handle($event);
        $this->events()->shouldHaveCount(2);
    }

    public function it_is_able_to_clear_domain_events(DomainEventInterface $event){

        $this->events()->shouldHaveCount(0);

        $this->handle($event);
        $this->events()->shouldHaveCount(1);

        $this->handle($event);
        $this->clear();
        $this->events()->shouldHaveCount(0);
    }
}
