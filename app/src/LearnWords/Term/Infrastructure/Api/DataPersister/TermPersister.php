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

namespace LearnWords\Term\Infrastructure\Api\DataPersister;


use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use League\Tactician\CommandBus;
use LearnWords\Term\Application\Delete\DeleteTerm;
use LearnWords\Term\Application\Save\SaveTerm;
use LearnWords\Term\Domain\Model\Term;

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
     * @inheritDoc
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Term;
    }

    /**
     * @inheritDoc
     */
    public function persist($data, array $context = [])
    {
        $command = SaveTerm::make($data);
        $this->commandBus->handle($command);
    }

    /**
     * @inheritDoc
     */
    public function remove($data, array $context = [])
    {
        $command = DeleteTerm::make($data);
        $this->commandBus->handle($command);
    }
}
