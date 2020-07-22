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

namespace LearnWords\Application\Word\UseCase;

use LearnWords\Domain\Word\WordRepository;
use PlanB\Edge\Application\UseCase\UseCaseInterface;

final class DeleteWordUseCase implements UseCaseInterface
{
    private WordRepository $wordRepository;

    public function __construct(WordRepository $wordRepository)
    {
        $this->wordRepository = $wordRepository;
    }

    public function handle(DeleteWord $command): void
    {
        $word = $command->getWord();
        $this->wordRepository->delete($word);
    }
}
