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

namespace LearnWords\Term\Domain\Model;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

final class TagList extends ArrayCollection
{
    /**
     * @var Collection
     */
    private Collection $collection;

    public static function collect(array $input = []): self
    {
        return static::wrap(new ArrayCollection($input));
    }

    public static function wrap(Collection $collection): self
    {
        return new self($collection);
    }

    private function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }


    /**
     * @param Term $term
     * @param string $label
     * @return $this
     */
    public function addTag(Term $term, string $label): self
    {
        if ($this->containsLabel($label)) {
            return $this;
        }

        $tag = new Tag(new TagId(), $label);
        $this->collection->add($tag);

        return $this;

    }

    /**
     * @param string $label
     * @return bool
     */
    public function containsLabel(string $label): bool
    {
        return $this->collection->exists(fn(int $index, Tag $tag) => $tag->isLike($label));
    }

    public function getCollection(): Collection
    {
        return $this->collection;
    }


}
