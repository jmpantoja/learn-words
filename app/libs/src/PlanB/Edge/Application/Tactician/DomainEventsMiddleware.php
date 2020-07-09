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

namespace PlanB\Edge\Application\Tactician;


use League\Tactician\Middleware;
use PlanB\Edge\Domain\Event\DomainEventsCollector;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use PlanB\Edge\Domain\Repository\EventStoreInterface;

final class DomainEventsMiddleware implements Middleware
{

    private EventStoreInterface $repository;

    public function __construct(EventStoreInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function execute($command, callable $next)
    {
        $eventDispatcher = DomainEventDispatcher::getInstance();
        $eventsCollector = new DomainEventsCollector();

        $eventDispatcher->setDomainEventsCollector($eventsCollector);

        $response = $next($command);
        $events = $eventsCollector->events();

        foreach ($events as $event){
            $this->repository->persist($event);
        }

        return $response;
    }
}
