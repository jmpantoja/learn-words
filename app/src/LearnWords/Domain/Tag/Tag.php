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


use Doctrine\ORM\PersistentCollection;
use PlanB\Edge\Domain\Entity\EntityInterface;


final class Tag implements EntityInterface
{
    private TagId $id;
    private string $tag;
    private PersistentCollection $terms;

    public function __construct(string $tag)
    {
        $this->id = new TagId();
        $this->tag = $tag;

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

    /**
     * @return PersistentCollection
     */
    public function getTerms()
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
