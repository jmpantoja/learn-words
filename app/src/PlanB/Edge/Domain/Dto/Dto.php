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

namespace PlanB\Edge\Domain\Dto;


use PlanB\Edge\Domain\PropertyExtractor\PropertyExtractor;
use Symfony\Component\PropertyAccess\PropertyAccessor;

abstract class Dto
{
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public static function fromObject(object $object): self
    {
        $input = PropertyExtractor::fromObject($object)
            ->toArray();

        return static::fromArray($input);
    }

    public static function fromArray(array $input): self
    {
        $dto = new static();

        $propertyAccess = new PropertyAccessor();

        foreach ($input as $key => $value) {
            if ($propertyAccess->isWritable($dto, $key)) {
                $propertyAccess->setValue($dto, $key, $value);
            }
        }
        return $dto;
    }
}
