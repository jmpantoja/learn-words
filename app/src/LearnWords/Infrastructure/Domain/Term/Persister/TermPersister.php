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

namespace LearnWords\Infrastructure\Domain\Term\Persister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use League\Tactician\CommandBus;
use LearnWords\Application\Term\UseCase\DeleteTerm;
use LearnWords\Application\Term\UseCase\SaveTerm;
use LearnWords\Domain\Term\Term;

final class TermPersister implements ContextAwareDataPersisterInterface
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
        return $data instanceof Term;
    }

    /**
     * @param mixed $data
     * @param mixed[] $context
     */
    public function persist($data, array $context = []): void
    {
        $command = new SaveTerm($data);
        $this->commandBus->handle($command);
    }

    /**
     * @param mixed $data
     * @param mixed[] $context
     */
    public function remove($data, array $context = []): void
    {
        $command = new DeleteTerm($data);
        $this->commandBus->handle($command);
    }
}
