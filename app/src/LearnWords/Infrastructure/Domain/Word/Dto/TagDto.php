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

namespace LearnWords\Infrastructure\Domain\Word\Dto;

use LearnWords\Domain\Word\Tag;
use PlanB\Edge\Domain\Entity\Dto;

final class TagDto extends Dto
{
    public ?string $tag = null;

    /**
     * @param Tag $entity
     * @return Tag
     */
    public function update($entity): Tag
    {
        return $entity->update($this->tag);
    }

    /**
     * @return Tag
     */
    public function create(): Tag
    {
        return new Tag($this->tag);
    }

}
