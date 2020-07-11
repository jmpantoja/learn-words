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

final class EventStore extends ServiceEntityRepository implements EventStoreInterface
{

    /**
     * @inheritDoc
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }


    public function persist(DomainEventInterface $domainEvent)
    {
        $event = new Event($domainEvent);
        $this->getEntityManager()->persist($event);
    }


}
