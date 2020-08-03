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


use LearnWords\Domain\Word\TagRepository;
use PlanB\Edge\Application\UseCase\UseCaseInterface;

final class SaveTagUseCase implements UseCaseInterface
{
    private TagRepository $tagRepository;

    public function __construct(TagRepository $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    public function handle(SaveTag $command): void
    {
        $tag = $command->getTag();
        $this->tagRepository->persist($tag);
    }
}
