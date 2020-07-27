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

namespace PlanB\Edge\Domain\VarType;


final class TypeUtils
{

    public const NULL = 'null';
    public const RESOURCE = 'resource';
    public const BOOL = 'bool';
    public const INT = 'int';
    public const LONG = 'long';
    public const INTEGER = 'integer';
    public const FLOAT = 'float';
    public const DOUBLE = 'double';
    public const REAL = 'double';
    public const NUMERIC = 'numeric';
    public const STRING = 'string';
    public const ARRAY = 'array';
    public const OBJECT = 'object';
    public const SCALAR = 'scalar';
    public const CALLABLE = 'callable';
    public const COUNTABLE = 'countable';
    public const STRINGABLE = 'stringable';
    public const ITERABLE = 'iterable';


    /**
     * @param mixed $value
     * @param string $type
     * @return bool
     */
    static public function isTypeOf($value, string $type): bool
    {
        if (static::isBuiltIn($type)) {
            $function = sprintf('is_%s', strtolower($type));
            return call_user_func($function, $value);
        }

        return is_a($value, $type);
    }

    static public function isBuiltIn(string $type): bool
    {
        $function = sprintf('is_%s', strtolower($type));
        return function_exists($function);
    }


}
