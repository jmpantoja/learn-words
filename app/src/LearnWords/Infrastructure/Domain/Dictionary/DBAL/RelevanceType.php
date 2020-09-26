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
use LearnWords\Domain\Dictionary\Relevance;
use PlanB\Edge\Domain\VarType\Exception\InvalidTypeException;

final class RelevanceType extends Type
{

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!$value instanceof Relevance) {
            throw new InvalidTypeException($value, Relevance::class);
        }

        return $value->getRelevance();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return new Relevance((int)$value);
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getIntegerTypeDeclarationSQL($fieldDeclaration);
    }

    public function getName()
    {
        return 'Relevance';
    }

}
