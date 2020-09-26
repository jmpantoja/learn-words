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

namespace LearnWords\Infrastructure\Domain\Dictionary\DBAL;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use LearnWords\Domain\Dictionary\Mp3Url;
use PlanB\Edge\Domain\VarType\Exception\InvalidTypeException;

final class Mp3UrlType extends Type
{

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (is_null($value)) {
            return null;
        }

        if (!$value instanceof Mp3Url) {
            throw new InvalidTypeException($value, Mp3Url::class);
        }

        return $value->getUrl();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Mp3Url((string)$value);
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }

    public function getName()
    {
        return 'Mp3Url';
    }

}
