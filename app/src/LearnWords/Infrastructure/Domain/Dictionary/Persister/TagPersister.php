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

namespace LearnWords\Infrastructure\Domain\Dictionary\Persister;

use League\Tactician\CommandBus;
use LearnWords\Application\Dictionary\UseCase\DeleteTag;
use LearnWords\Application\Dictionary\UseCase\SaveTag;
use LearnWords\Domain\Dictionary\Tag;
use PlanB\Edge\Domain\DataPersister\DataPersisterInterface;

final class TagPersister implements DataPersisterInterface
{
    /**
     * @var CommandBus
     */
    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param mixed $data
     * @param mixed[] $context
     */
    public function supports($data): bool
    {
        return $data instanceof Tag;
    }

    /**
     * @param mixed $data
     * @param mixed[] $context
     */
    public function persist($data): void
    {
        $command = new SaveTag($data);
        $this->commandBus->handle($command);
    }

    /**
     * @param mixed $data
     * @param mixed[] $context
     */
    public function remove($data): void
    {
        $command = new DeleteTag($data);
        $this->commandBus->handle($command);
    }
}
