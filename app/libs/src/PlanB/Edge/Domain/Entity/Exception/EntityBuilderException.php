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

namespace PlanB\Edge\Domain\Entity\Exception;


use DomainException;
use Throwable;

final class EntityBuilderException extends DomainException
{

    /**
     * @inheritDoc
     */
    private function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public static function noSuchProperty(string $property): self
    {
        $message = sprintf('La propiedad "%s" no existe', $property);
        return new self($message);
    }

    public static function typeError(string $property, $value)
    {
        $format = 'El valor que se intenta asignar a la propiedad "%s" no es correcto (%s)';
        $message = sprintf($format, ...[
            $property,
            $value
        ]);

        return new self($message);
    }
}
