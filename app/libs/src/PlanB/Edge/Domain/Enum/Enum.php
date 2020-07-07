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


abstract class Enum extends \MyCLabs\Enum\Enum
{
    public static function create(string $name): ?self
    {
        if (static::hasKey($name)) {
            return static::byKey($name);
        }

        if (static::isValid($name)) {
            $name = static::search($name);
            return static::byKey($name);
        }
        return null;
    }


    public static function byKey(string $value): Enum
    {
        return static::__callStatic($value, []);
    }

    public static function keys(): array
    {
        return static::toArray();
    }

    public static function hasKey(string $value)
    {
        return static::isValidKey($value);
    }

    /**
     * Compare this enumerator against another and check if it's the same.
     *
     * @param Enum|null|bool|int|float|string|array $enumerator An enumerator object or value
     * @return bool
     */
    final public function is($enumerator)
    {
        return $this->equals($enumerator);
    }

    public function __toString()
    {
        return $this->getKey();
    }
}
