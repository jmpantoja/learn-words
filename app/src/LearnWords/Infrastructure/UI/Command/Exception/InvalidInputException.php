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

namespace LearnWords\Infrastructure\UI\Command\Exception;


use LearnWords\Domain\Dictionary\Lang;
use RuntimeException;

final class InvalidInputException extends RuntimeException
{
    /**
     * @inheritDoc
     */
    private function __construct($format, ...$arguments)
    {
        $message = sprintf($format, ...$arguments);
        parent::__construct($message);
    }


    public static function invalidLang(string $input)
    {
        $format = 'El valor del idioma "%s" no es correcto, se esperaba [%s]';
        $expected = implode(' | ', Lang::toArray());

        return new self($format, ...[
            $input,
            $expected
        ]);
    }

    public static function invalidSource(\SplFileInfo $fileInfo)
    {

        $format = 'La ruta "%s" no es un fichero o no tiene permisos de lectura';
        return new self($format, ...[
            $fileInfo->getPathname()
        ]);
    }

}
