<?php

namespace spec\PlanB\Edge\Application\Tactician;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Application\Tactician\DomainEventsMiddleware;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use PlanB\Edge\Domain\Event\DomainEventInterface;
use PlanB\Edge\Domain\Event\DomainEventsCollector;
use PlanB\Edge\Domain\Repository\EventStoreInterface;
use stdClass;

class DomainEventsMiddlewareSpec extends ObjectBehavior
{
    public function let(EventStoreInterface $eventStore,
                        DomainEventsCollector $eventsCollector)
    {
        $this->beConstructedWith($eventStore);

        $eventDispatcher = DomainEventDispatcher::getInstance();
        $eventDispatcher->setDomainEventsCollector($eventsCollector->getWrappedObject());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DomainEventsMiddleware::class);
    }

    public function it_is_able_to_execute_a_command(EventStoreInterface $eventStore,
                                                    DomainEventInterface $eventA,
                                                    DomainEventInterface $eventB,
                                                    DomainEventsCollector $eventsCollector)
    {

        $eventsCollector->getEvents()->willReturn([
            $eventA,
            $eventB
        ]);

        $this->execute(new stdClass(), fn() => 'response')
            ->shouldReturn('response');

        $eventStore->persist($eventA)->shouldBeCalledOnce();
        $eventStore->persist($eventB)->shouldBeCalledOnce();



    }
}
