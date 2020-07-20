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


if (!function_exists('is_stringable')) {

    /**
     * @param mixed $value
     * @return bool
     */
    function is_stringable($value): bool
    {
        return is_null($value)
            || is_scalar($value)
            || (is_object($value) && method_exists($value, '__toString'));
    }

}
