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


use Doctrine\Common\Collections\ArrayCollection;
use PlanB\Edge\Domain\Collection\Exception\InvalidClassnameException;
use PlanB\Edge\Domain\Collection\Exception\InvalidCollectionElement;
use Traversable;

abstract class TypedList extends ArrayCollection
{
    public static function empty(): self
    {
        return new static([]);
    }

    /**
     * @param iterable $input
     * @return static
     */
    public static function collect(iterable $input): self
    {
        if ($input instanceof Traversable) {
            $input = iterator_to_array($input);
        }

        return new static($input);
    }


    final protected function __construct(array $input)
    {
        foreach ($input as $value) {
            $this->add($value);
        }
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
