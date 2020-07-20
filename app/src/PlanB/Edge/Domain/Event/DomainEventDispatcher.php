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

use BadMethodCallException;
use Symfony\Component\EventDispatcher\EventDispatcher as BaseEventDispatcher;

class DomainEventDispatcher extends BaseEventDispatcher
{
    protected static ?DomainEventDispatcher $instance = null;
    private DomainEventsCollector $eventsCollector;

    final private function __construct()
    {
        parent::__construct();
        $this->eventsCollector = new DomainEventsCollector();
    }

    public static function getInstance(): self
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    public function setDomainEventsCollector(DomainEventsCollector $eventsCollector): self
    {
        $this->eventsCollector = $eventsCollector;
        return $this;
    }

    /**
     * @return DomainEventsCollector
     */
    public function getEventsCollector(): DomainEventsCollector
    {
        return $this->eventsCollector;
    }

    public function dispatch($event)
    {
        if ($event instanceof DomainEventInterface) {
            $this->eventsCollector->handle($event, get_class($event));
        }

        return parent::dispatch($event);
    }

    public function __clone()
    {
        throw new BadMethodCallException('Este objeto no puede ser clonado');
    }

    public function __wakeup()
    {
        throw new BadMethodCallException('Este objeto no puede ser deserializado');
    }

}
