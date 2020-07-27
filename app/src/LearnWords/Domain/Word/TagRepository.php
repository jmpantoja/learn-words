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

namespace LearnWords\Domain\Word;


interface TagRepository
{
    public function persist(Tag $tag): void ;

    public function delete(Tag $tag): void ;

    public function findByLabel(string $label): ?Tag;
}
