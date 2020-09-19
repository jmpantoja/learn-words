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
use LearnWords\Domain\Dictionary\TagRepository;
use PlanB\Edge\Application\UseCase\UseCaseInterface;

final class DeleteEntryUseCase implements UseCaseInterface
{
    private EntryRepository $entryRepository;

    public function __construct(EntryRepository $entryRepository)
    {
        $this->entryRepository = $entryRepository;
    }

    public function handle(DeleteEntry $command): void
    {
        $entry = $command->getEntry();
        $this->entryRepository->delete($entry);
    }
}
