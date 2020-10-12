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

namespace LearnWords\Application\Dictionary\UseCase;


use LearnWords\Domain\Dictionary\EntryRepository;
use LearnWords\Domain\Dictionary\IrregularRepository;
use LearnWords\Infrastructure\Domain\Dictionary\Repository\IrregularDoctrineRepository;
use PlanB\Edge\Application\UseCase\UseCaseInterface;

final class SaveIrregularUseCase implements UseCaseInterface
{
    private IrregularDoctrineRepository $repository;

    public function __construct(IrregularRepository $repository)
    {
        $this->repository = $repository;
    }

    public function handle(SaveIrregular $command): void
    {
        $irregular = $command->getIrregular();
        $this->repository->persist($irregular);
    }
}
