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

namespace LearnWords\Infrastructure\Domain\Dictionary\Dto;


use Doctrine\Common\Collections\Collection;
use LearnWords\Domain\Dictionary\Word;
use PlanB\Edge\Domain\Dto\Dto;
use PlanB\Edge\Domain\Entity\EntityId;

final class EntryDto extends Dto
{
    public ?EntityId $id;
    public ?Word $word;
    public ?Collection $tags;
    public ?Collection $questions;


    /**
     * @param Word|null $word
     * @return EntryDto
     */
    public function setWord(?Word $word): self
    {
        $this->word = $word;
        return $this;
    }
}
