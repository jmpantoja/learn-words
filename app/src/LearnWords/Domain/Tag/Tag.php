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

namespace LearnWords\Domain\Tag;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;
use PlanB\Edge\Domain\Entity\EntityInterface;


final class Tag implements EntityInterface
{
    private TagId $id;
    private string $tag;
    private Collection $terms;

    public function __construct(string $tag)
    {
        $this->id = new TagId();
        $this->tag = $tag;
        $this->terms = new ArrayCollection();

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


    public function getTerms(): Collection
    {
        return $this->terms;
    }


    public function isLike(string $label): bool
    {
        return 0 === strcasecmp($this->tag, $label);
    }

    public function update(string $label): self
    {
        $this->tag = $label;
        return $this;
    }
}
