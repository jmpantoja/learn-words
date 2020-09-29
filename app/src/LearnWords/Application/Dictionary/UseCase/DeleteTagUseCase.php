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

use LearnWords\Domain\Dictionary\TagRepository;
use PlanB\Edge\Application\UseCase\UseCaseInterface;

final class DeleteTagUseCase implements UseCaseInterface
{
    private TagRepository $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function handle(DeleteTag $command): void
    {
        $word = $command->getTag();
        $this->tagRepository->delete($word);
    }
}