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

namespace LearnWords\Application\User\UseCase;


use LearnWords\Domain\User\UserRepository;
use PlanB\Edge\Application\UseCase\UseCaseInterface;

final class DeleteUserUseCase implements UseCaseInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(DeleteUser $command): void
    {
        $user = $command->getUser();
        $this->userRepository->delete($user);
    }
}
