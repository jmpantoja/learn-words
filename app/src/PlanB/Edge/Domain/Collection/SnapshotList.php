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

namespace PlanB\Edge\Domain\Collection;


use Doctrine\Common\Collections\AbstractLazyCollection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Traversable;

class SnapshotList extends AbstractLazyCollection
{
    private array $snapshot;


    private function __construct(Collection $input)
    {
        $this->initialize();

        $this->snapshot = $input->toArray();

        while ($input instanceof AbstractLazyCollection) {
            $input = $input->collection;
        }

        $this->collection = clone $input;
    }

    public static function collect(?iterable $input = null): self
    {
        $input = $input ?? [];
        if ($input instanceof Collection) {
            return new static($input);
        }

        if ($input instanceof Traversable) {
            $input = iterator_to_array($input);
        }

        return new static(new ArrayCollection($input));
    }

    public static function init(?iterable $input = null): self
    {
        $snapshotList = static::collect();
        foreach ($input as $item) {
            $snapshotList->add($item);
        }

        return $snapshotList;
    }

    /**
     * @inheritDoc
     */
    public function __clone()
    {
        $this->collection = clone $this->collection;
    }

    public function clone(): self
    {
        return clone $this;
    }

    public function eachInsert(callable $callback): self
    {
        $items = $this->getInsertDiff();
        foreach ($items as $key => $item) {
            call_user_func($callback, $item, $key);
        }

        return $this;
    }

    public function getInsertDiff(): array
    {
        $keys = $this->collection->getKeys();

        $inserted = array_filter($keys, function ($key) {
            return !isset($this->snapshot[$key]);
        });

        return array_map(function ($key) {
            return $this->collection->get($key);
        }, $inserted);
    }

    public function eachDelete(callable $callback): self
    {
        $items = $this->getDeleteDiff();
        foreach ($items as $key => $item) {
            call_user_func($callback, $item, $key);
        }

        return $this;
    }

    public function getDeleteDiff(): array
    {
        $keys = array_keys($this->snapshot);

        $deleted = array_filter($keys, function ($key) {
            return !$this->collection->containsKey($key);
        });

        return array_map(function ($key) {
            return $this->snapshot[$key];
        }, $deleted);

    }

    public function eachUpdate(callable $callback): self
    {
        $items = $this->getUpdateDiff();

        foreach ($items as $key => $item) {
            call_user_func($callback, $key, $item);
        }

        return $this;
    }

    public function getUpdateDiff(): array
    {

        $keys = array_keys($this->snapshot);

        $updated = array_filter($keys, function ($key) {
            if (!$this->collection->containsKey($key)) {
                return false;
            }

            $original = $this->snapshot[$key];
            $current = $this->collection->get($key);

            return $original != $current;
        });

        return array_map(function ($key) {
            return $this->collection->get($key);
        }, $updated);
    }

    /**
     * @inheritDoc
     */
    protected function doInitialize()
    {
    }

}
