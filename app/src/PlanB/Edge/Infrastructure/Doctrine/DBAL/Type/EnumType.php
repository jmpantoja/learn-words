<?php

declare(strict_types=1);

namespace PlanB\Edge\Infrastructure\Doctrine\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use PlanB\Edge\Domain\Enum\Enum;
use PlanB\Edge\Domain\VarType\Exception\InvalidTypeException;


abstract class EnumType extends Type
{

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        if (!($value instanceof Enum)) {
            throw new InvalidTypeException($value, Enum::class);
        }

        return $value->getKey();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        return $this->byKey($value, $platform);
    }

    /**
     * @param string $value
     * @return Enum
     */
    abstract public function byKey(string $value, AbstractPlatform $platform): Enum;

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param mixed[] $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }
}
