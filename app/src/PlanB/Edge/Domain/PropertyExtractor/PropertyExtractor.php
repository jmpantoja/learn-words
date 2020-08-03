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

namespace PlanB\Edge\Domain\PropertyExtractor;


use ReflectionClass;
use ReflectionObject;
use ReflectionProperty;

final class PropertyExtractor
{
    private object $object;
    private ?ReflectionObject $reflection = null;

    final private function __construct(object $object)
    {
        $this->object = $object;
    }

    static public function fromObject(object $object): self
    {
        return new self($object);
    }

    /**
     * @return mixed|null
     */
    public function id()
    {
        $reflection = $this->getReflection();
        if (!$reflection->hasProperty('id')) {
            return null;
        }

        $reflection = $this->getReflection();

        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        if (!$property->isInitialized($this->object)) {
            return null;
        }
        return $property->getValue($this->object);
    }

    private function getReflection(): ReflectionObject
    {
        if (null === $this->reflection) {
            $this->reflection = new ReflectionObject($this->object);
        }
        return $this->reflection;
    }

    public function toArray(): array
    {
        $reflection = $this->getReflection();

        $data = [];
        do {
            $data[] = $this->getValues($reflection);
        } while ($reflection = $reflection->getParentClass());

        $data = array_reverse($data);
        return array_merge(...$data);
    }

    private function getValues(ReflectionClass $reflection): array
    {
        $data = [];
        foreach ($reflection->getProperties() as $property) {
            $data[] = $this->getValue($property);
        }
        $data = array_filter($data);
        return array_merge(...$data);
    }

    private function getValue(ReflectionProperty $property): ?array
    {
        if ($property->isStatic()) {
            return null;
        }

        $name = $property->name;
        $property->setAccessible(true);

        return [$name => $property->getValue($this->object)];
    }

}
