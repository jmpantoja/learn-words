<?php

namespace spec\PlanB\Edge\Infrastructure\Doctrine\Repository;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Entity\Event;
use PlanB\Edge\Domain\Event\DomainEventInterface;
use PlanB\Edge\Infrastructure\Doctrine\Repository\EventStore;
use Prophecy\Argument;
use Symfony\Component\Serializer\SerializerInterface;

class EventStoreSpec extends ObjectBehavior
{
    public function let(SerializerInterface $serializer,
                        ManagerRegistry $manager,
                        EntityManagerInterface $repository,
                        ClassMetadata $classMetadata)
    {
        $this->beConstructedWith($serializer, $manager);
        $manager->getManagerForClass(Event::class)->willReturn($repository);

        $repository->getClassMetadata(Event::class)->willReturn($classMetadata);

    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(EventStore::class);
    }

    public function it_is_able_to_persist_an_event(SerializerInterface $serializer,
                                                   DomainEventInterface $domainEvent,
                                                   EntityManagerInterface $repository)
    {

        $serializer->serialize(Argument::type('array'), 'json', Argument::type('array'))
            ->willReturn('eventData');

        $domainEvent->getWhen()->willReturn(new DateTimeImmutable());

        $this->persist($domainEvent);

        $repository->persist(Argument::type(Event::class))
            ->shouldBeCalledOnce();
    }

    public function it_is_able_to_create_a_event_object(SerializerInterface $serializer,
                                                        DomainEventInterface $domainEvent)
    {
        $date = new DateTimeImmutable();

        $serializer->serialize(Argument::type('array'), 'json', Argument::type('array'))
            ->willReturn('eventData');


        $domainEvent->getWhen()->willReturn($date);

        $eventObject = $this->createEventObject($domainEvent);
        $eventObject->getName()->shouldStartWith('Double.');
        $eventObject->getEvent()->shouldStartWith('eventData');

        $eventObject->getDate($date);
    }
}
