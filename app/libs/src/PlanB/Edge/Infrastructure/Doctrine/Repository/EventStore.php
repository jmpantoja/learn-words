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
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Domain\Entity\Event;
use PlanB\Edge\Domain\Event\DomainEventInterface;
use PlanB\Edge\Domain\Repository\EventStoreInterface;
use PlanB\Edge\Infrastructure\Symfony\Serializer\Normalizer\DomainEventNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;

final class EventStore extends ServiceEntityRepository implements EventStoreInterface
{

    /**
     * @inheritDoc
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }


    public function persist(DomainEventInterface $event)
    {
        $event = new Event(...[
            get_class($event),
            $this->serialize($event),
            $event->when()
        ]);

        $this->getEntityManager()->persist($event);
    }

    /**
     * @param DomainEventInterface $event
     * @return string
     */
    private function serialize(DomainEventInterface $event): string
    {
        $encoder = [new JsonEncoder()];
        $normalizer = [new DomainEventNormalizer()];

        $serializer = new Serializer($normalizer, $encoder);
        return $serializer->serialize($event, 'json');
    }
}
