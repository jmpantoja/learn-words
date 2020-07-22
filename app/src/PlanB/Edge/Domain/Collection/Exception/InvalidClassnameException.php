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

namespace PlanB\Edge\Domain\Collection\Exception;


use LogicException;

final class InvalidClassnameException extends LogicException implements CollectionExceptionInterface
{

    public function __construct(string $className)
    {
        $message = sprintf("La clase '%s' no existe", $className);
        parent::__construct($message);
    }
}
