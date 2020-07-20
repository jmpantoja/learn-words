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

namespace PlanB\Edge\Shared\Exception;

use InvalidArgumentException;

final class InvalidTypeException extends InvalidArgumentException
{
    /**
     * InvalidTypeException constructor.
     * @param mixed $value
     * @param string $expectedTypes
     */
    public function __construct($value, string $expectedTypes)
    {
        $type = \is_object($value) ? \get_class($value) : \gettype($value);
        $message = sprintf('Se esperaba un argumento de tipo "%s", pero se ha pasado un "%s"', $expectedTypes, $type);

        parent::__construct($message);
    }
}
