<?php

namespace spec\PlanB\Edge\Domain\Event;

use BadMethodCallException;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use PlanB\Edge\Domain\Event\DomainEventInterface;
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

    public function it_deals_domain_events_by_event_collector(DomainEventsCollector $eventsCollector,
                                                              DomainEventInterface $domainEvent,
                                                              Event $event)
    {
        $eventsCollector->handle(Argument::any())->willReturn($eventsCollector);

        $this->setDomainEventsCollector($eventsCollector);
        $this->getEventsCollector()->shouldReturn($eventsCollector);

        $this->dispatch($domainEvent);
        $this->dispatch($event);

        $eventsCollector->handle($domainEvent)
            ->shouldHaveBeenCalledOnce();

        $eventsCollector->handle($event)
            ->shouldNotHaveBeenCalled();
    }

    public function it_throws_an_exception_when_try_to_clone_the_singleton()
    {
        $this->shouldThrow(BadMethodCallException::class)->during('__clone');
    }

    public function it_throws_an_exception_when_try_to_serialize_the_singleton()
    {
        $this->shouldThrow(BadMethodCallException::class)->during('__wakeup');
    }

}
