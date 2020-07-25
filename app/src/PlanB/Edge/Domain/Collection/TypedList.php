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
use PlanB\Edge\Domain\Collection\Exception\InvalidClassnameException;
use PlanB\Edge\Domain\Collection\Exception\InvalidCollectionElement;
use Traversable;

abstract class TypedList extends AbstractLazyCollection
{
    public static function empty(): self
    {
        return static::collect([]);
    }

    /**
     * @param iterable $input
     * @return static
     */
    public static function collect(iterable $input): self
    {
        if ($input instanceof Collection) {
            return new static($input);
        }

        if ($input instanceof Traversable) {
            $input = iterator_to_array($input);
        }

        return new static(new ArrayCollection($input));
    }

    final protected function __construct(Collection $input)
    {
        foreach ($input as $value) {
            $this->ensureValueIsValid($value);
        }

        $this->initialize();
        $this->collection = $input;
    }

    /**
     * @inheritDoc
     */
    protected function doInitialize()
    {
        if (!($this->collection instanceof Collection)) {
            return;
        }

        $this->collection->clear();
    }

    /**
     * @param mixed $value
     * @return static
     */
    public function add($value): self
    {
        $this->ensureValueIsValid($value);
        parent::add($value);
        return $this;
    }

    /**
     * @param int|string $key
     * @param mixed $value
     * @return $this
     */
    public function set($key, $value): self
    {
        $this->ensureValueIsValid($value);
        parent::set($key, $value);

        return $this;
    }

    /**
     * @param mixed $value
     */
    protected function ensureValueIsValid($value): void
    {
        $type = $this->getType();

        $functionName = 'is_' . $type;


        if (!class_exists($type) and !function_exists($functionName)) {
            throw new InvalidClassnameException($type);
        }

        if (is_object($value) and !is_a($value, $type)) {
            throw new InvalidCollectionElement($value, $type);
        }

        if (!function_exists($functionName)) {
            return;
        }

        if (!is_object($value) and !$functionName($value)) {
            throw new InvalidCollectionElement($value, $type);
        }

    }

    abstract public function getType(): string;

    /**
     * @param callable $callback
     * @param mixed $initial
     * @return mixed
     */
    public function reduce(callable $callback, $initial = null)
    {
        return array_reduce($this->toArray(), $callback, $initial);
    }


}
