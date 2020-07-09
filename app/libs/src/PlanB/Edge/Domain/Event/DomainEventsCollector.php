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

namespace PlanB\Edge\Domain\Event;


class DomainEventsCollector
{
    private array $events = [];

    public function handle(DomainEventInterface $event)
    {
        $this->events[] = $event;
        return $this;
    }

    /**
     * @return array
     */
    public function events(): array
    {
        return $this->events;
    }

    public function clear(): self
    {
        $this->events = [];
        return $this;
    }

}
