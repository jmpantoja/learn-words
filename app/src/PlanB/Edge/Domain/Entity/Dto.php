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

namespace PlanB\Edge\Domain\Entity;


use ArrayAccess;
use PlanB\Edge\Domain\PropertyExtractor\PropertyExtractor;

abstract class Dto implements ArrayAccess
{

    public static function fromObject(object $entity): self
    {
        $data = PropertyExtractor::fromObject($entity)
            ->toArray();
        return static::fromArray($data);
    }

    public static function fromArray(array $data): self
    {
        $properties = array_keys(get_class_vars(static::class));
        $dto = static::byDefault();

        foreach ($properties as $key) {
            $dto[$key] = $data[$key] ?? $dto[$key] ?? null;
        }

        return $dto;
    }

    public static function byDefault(): self
    {
        /* @phpstan-ignore-next-line */
        return new static();
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->{$offset});
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->{$offset};
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->{$offset} = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        if (isset($this[$offset])) {
            $this->{$offset} = null;
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * @param mixed $entity
     * @return object
     */
    public function process($entity = null): object
    {
        if (null === $entity) {
            return $this->create();
        }

        return $this->update($entity);
    }

    abstract public function create(): object;

    /**
     * @param mixed $entity
     * @return object
     */
    abstract public function update($entity): object;

}
