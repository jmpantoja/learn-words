<?php

namespace spec\PlanB\Edge\Domain\Entity\Traits;

use PhpSpec\ObjectBehavior;
use PhpSpec\Wrapper\Collaborator;
use PlanB\Edge\Domain\Entity\Traits\NotifyEvents;
use PlanB\Edge\Domain\Event\DomainEvent;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use PlanB\Edge\Domain\Event\DomainEventsCollector;
use Prophecy\Argument;

class NotifyEventsSpec extends ObjectBehavior
{
    public function let(DomainEventsCollector $eventsCollector)
    {
        $eventsCollector->handle(Argument::cetera())->willReturn($eventsCollector);
        $this->beAnInstanceOf(Aggregate::class);

        DomainEventDispatcher::getInstance()
            ->setDomainEventsCollector($eventsCollector->getWrappedObject());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Aggregate::class);
    }

    public function it_is_able_to_registry_an_event(DomainEventsCollector $eventsCollector,
                                                    DomainEvent $event)
    {
        $this->notify($event);
        $eventsCollector->handle($event)
            ->shouldHaveBeenCalledOnce();
    }
}

class Aggregate
{
    use NotifyEvents;
}
