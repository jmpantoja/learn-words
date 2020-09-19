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

namespace LearnWords\Application\Dictionary\Subscriber;


use League\Tactician\CommandBus;
use LearnWords\Application\Dictionary\UseCase\SaveEntry;
use LearnWords\Domain\Dictionary\EntryPersistence\EntryHasBeenImported;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class EntrySubscriber implements EventSubscriberInterface
{

    /**
     * @var CommandBus
     */
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public static function getSubscribedEvents()
    {
        return [
            EntryHasBeenImported::class => ['onImport']
        ];
    }

    public function onImport(EntryHasBeenImported $event)
    {
        $command = new SaveEntry($event->getEntry());
        $this->commandBus->handle($command);
  //      die('xxx');
    }
}
