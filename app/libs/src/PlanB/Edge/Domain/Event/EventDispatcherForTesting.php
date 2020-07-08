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


use PlanB\Edge\Shared\Invokable;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class EventDispatcherForTesting extends EventDispatcher
{

    /**
     * @var callable
     */
    private $globalListener;

    public function addGlobalListener(Invokable $globalListner)
    {
        $this->globalListener = $globalListner;
    }

    public function dispatch($event/*, string $eventName = null*/)
    {
        $eventName = 1 < \func_num_args() ? func_get_arg(1) : null;

        if (\is_object($event)) {
            $eventName = $eventName ?? \get_class($event);
        } elseif (\is_string($event) && (null === $eventName || $eventName instanceof ContractsEvent || $eventName instanceof Event)) {
            @trigger_error(sprintf('Calling the "%s::dispatch()" method with the event name as the first argument is deprecated since Symfony 4.3, pass it as the second argument and provide the event object as the first argument instead.', EventDispatcherInterface::class), E_USER_DEPRECATED);
            $swap = $event;
            $event = $eventName ?? new Event();
            $eventName = $swap;
        } else {
            throw new \TypeError(sprintf('Argument 1 passed to "%s::dispatch()" must be an object, "%s" given.', EventDispatcherInterface::class, \is_object($event) ? \get_class($event) : \gettype($event)));
        }

        parent::dispatch($event);

        if (is_callable($this->globalListener)) {
            $this->callListeners([$this->globalListener], $eventName, $event);
        }

        return $event;
    }


}
