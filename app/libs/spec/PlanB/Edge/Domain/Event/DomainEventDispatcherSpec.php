<?php

namespace spec\PlanB\Edge\Domain\Event;

use BadMethodCallException;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEventInterface;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use PlanB\Edge\Domain\Event\DomainEventsCollector;
use Prophecy\Argument;
use Symfony\Contracts\EventDispatcher\Event;

class DomainEventDispatcherSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedThrough('getInstance');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DomainEventDispatcher::class);
    }

    public function it_is_a_singleton()
    {
        $instance = DomainEventDispatcher::getInstance();
        $this->getInstance()->shouldReturn($instance);
    }

    public function it_deals_domain_events_by_event_collector(DomainEventsCollector $eventsCollector, DomainEventInterface $domainEvent, Event $event)
    {
        $this->setDomainEventsCollector($eventsCollector);
        $this->dispatch($domainEvent);
        $this->dispatch($event);

        $eventsCollector->handle($domainEvent, Argument::type('string'))->shouldHaveBeenCalledOnce();
        $eventsCollector->handle($event, Argument::type('string'))->shouldNotHaveBeenCalled();
    }

}
