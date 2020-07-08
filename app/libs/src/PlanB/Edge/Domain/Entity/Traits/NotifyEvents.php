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

namespace PlanB\Edge\Domain\Entity\Traits;


use PlanB\Edge\Domain\Event\DomainEvent;
use PlanB\Edge\Domain\Event\EventDispatcher;

trait NotifyEvents
{
    final public function notify(DomainEvent $domainEvent): void
    {
        EventDispatcher::getInstance()
            ->dispatch($domainEvent);
    }
}
