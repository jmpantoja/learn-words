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

namespace LearnWords\Infrastructure\Domain\Word\Persister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use League\Tactician\CommandBus;
use LearnWords\Application\Word\UseCase\DeleteTag;
use LearnWords\Application\Word\UseCase\DeleteWord;
use LearnWords\Application\Word\UseCase\SaveTag;
use LearnWords\Application\Word\UseCase\SaveWord;
use LearnWords\Domain\Word\Tag;
use LearnWords\Domain\Word\Word;

final class TagPersister implements ContextAwareDataPersisterInterface
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
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Tag;
    }

    /**
     * @param mixed $data
     * @param mixed[] $context
     */
    public function persist($data, array $context = []): void
    {
        $command = new SaveTag($data);
        $this->commandBus->handle($command);
    }

    /**
     * @param mixed $data
     * @param mixed[] $context
     */
    public function remove($data, array $context = []): void
    {
        $command = new DeleteTag($data);
        $this->commandBus->handle($command);
    }
}
