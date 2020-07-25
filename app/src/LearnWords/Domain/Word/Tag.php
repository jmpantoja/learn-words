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


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use LearnWords\Domain\Word\TagPersistence\TagHasBeenCreated;
use LearnWords\Domain\Word\TagPersistence\TagHasBeenUpdated;
use PlanB\Edge\Domain\Entity\EntityInterface;
use PlanB\Edge\Domain\Entity\Traits\NotifyEvents;


class Tag implements EntityInterface
{
    use NotifyEvents;
    private TagId $id;
    private string $tag;
    private Collection $words;

    public function __construct(string $tag)
    {
        $this->id = new TagId();
        $this->words = new ArrayCollection();
        $this->notify(new TagHasBeenCreated($this));

        $this->update($tag);

    }

    public function update(string $tag): self
    {
        $this->tag = $tag;
        $this->notify(new TagHasBeenUpdated($this));
        return $this;
    }

    /**
     * @return TagId
     */
    public function getId(): TagId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }


    public function getWords(): Collection
    {
        return $this->words;
    }
}
