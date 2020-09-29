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

namespace LearnWords\Infrastructure\Domain\User\Persister;


use League\Tactician\CommandBus;
use LearnWords\Application\User\UseCase\SaveUser;
use LearnWords\Domain\User\User;
use PlanB\Edge\Domain\DataPersister\DataPersisterInterface;

final class UserPersister implements DataPersisterInterface
{

    private CommandBus $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function supports($user): bool
    {
        return $user instanceof User;
    }

    public function persist($user)
    {
        $command = new SaveUser($user);
        $this->commandBus->handle($command);
    }

    public function remove($user)
    {
        $command = new SaveUser($user);
        $this->commandBus->handle($command);
    }
}
