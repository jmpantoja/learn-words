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

namespace LearnWords\Domain\Dictionary;


use LearnWords\Domain\Dictionary\TagPersistence\TagHasBeenCreated;
use LearnWords\Domain\Dictionary\TagPersistence\TagHasBeenUpdated;
use PlanB\Edge\Domain\Entity\Traits\NotifyEvents;

class Tag
{
    use NotifyEvents;

    private TagId $id;
    private string $tag;

    public function __construct(string $tag)
    {
        $this->id = new TagId();
        $this->tag = trim($tag);

        $this->notify(new TagHasBeenCreated($this));
    }

    public function update(string $tag): self
    {
        $this->tag = $tag;
        $this->notify(new TagHasBeenUpdated($this));
        return $this;
    }

    public function getId(): TagId
    {
        return $this->id;
    }

    public function getTag(): string
    {
        return $this->tag;
    }
}
