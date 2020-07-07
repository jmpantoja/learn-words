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

namespace LearnWords\Term\Application\Delete;



use LearnWords\Term\Domain\Repository\TermRepositoryInterface;
use PlanB\Edge\Application\UseCase\UseCaseInterface;

final class DeleteTermUseCase implements UseCaseInterface
{

    private TermRepositoryInterface $termRepository;

    public function __construct(TermRepositoryInterface $termRepository)
    {
        $this->termRepository = $termRepository;
    }

    public function handle(DeleteTerm $command)
    {
        $term = $command->entity();
        return $this->termRepository->delete($term);
    }
}
