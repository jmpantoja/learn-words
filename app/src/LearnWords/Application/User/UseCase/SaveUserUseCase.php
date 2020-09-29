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


use LearnWords\Application\Dictionary\UseCase\SaveTag;
use LearnWords\Domain\Dictionary\TagRepository;
use LearnWords\Domain\User\UserRepository;
use PlanB\Edge\Application\UseCase\UseCaseInterface;

final class SaveUserUseCase implements UseCaseInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function handle(SaveUser $command): void
    {
        $user = $command->getUser();
        $this->userRepository->persist($user);
    }
}
