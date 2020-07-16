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

namespace PlanB\Edge\Domain\Enum;


use LogicException;

abstract class Enum extends \MyCLabs\Enum\Enum
{
    public static function make($value): ?self
    {
        if ($value instanceof static) {
            return $value;
        }

        if (static::hasKey($value)) {
            return static::byKey($value);
        }

        if (static::hasValue($value)) {
            return self::byValue($value);
        }

        $message = sprintf('No se puede crear un objeto "%s" a partir del valor "%s"', static::class, $value);
        throw new LogicException($message);
    }


    public static function byKey(string $value): Enum
    {
        return static::__callStatic($value, []);
    }

    /**
     * @param $value
     * @return Enum
     */
    public static function byValue($value): Enum
    {
        $value = static::search($value);
        return static::byKey($value);
    }


    public static function hasKey(string $key): bool
    {
        return static::isValidKey($key);
    }

    public static function hasValue(string $value): bool
    {
        return static::isValid($value);
    }

    /**
     * Compare this enumerator against another and check if it's the same.
     *
     * @param Enum|null|bool|int|float|string|array $enumerator An enumerator object or value
     * @return bool
     */
    final public function is($enumerator): bool
    {
        return $this->equals($enumerator);
    }

    public function __toString()
    {
        return $this->getKey();
    }
}
