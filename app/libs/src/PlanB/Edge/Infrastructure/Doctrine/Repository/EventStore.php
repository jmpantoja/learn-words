<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PlanB\Edge\Infrastructure\Doctrine\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PlanB\Edge\Domain\Entity\Event;
use PlanB\Edge\Domain\Event\DomainEventInterface;
use PlanB\Edge\Domain\Repository\EventStoreInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class EventStore extends ServiceEntityRepository implements EventStoreInterface
{
    /**
     * @var SerializerInterface
     */
    private SerializerInterface $serializer;

    /**
     * @inheritDoc
     */
    public function __construct(SerializerInterface $serializer, ManagerRegistry $registry)
    {
        $this->serializer = $serializer;
        parent::__construct($registry, Event::class);
    }

    public function persist(DomainEventInterface $domainEvent)
    {
        $event = $this->createEventObject($domainEvent);

        $this->getEntityManager()->persist($event);
    }

    /**
     * @param DomainEventInterface $domainEvent
     * @return Event
     */
    public function createEventObject(DomainEventInterface $domainEvent): Event
    {
        $data = $this->serializer->serialize($domainEvent, 'json', [
            ObjectNormalizer::IGNORED_ATTRIBUTES => ['when']
        ]);

        return new Event(...[
            get_class($domainEvent),
            $data,
            $domainEvent->getWhen()
        ]);
    }


}
