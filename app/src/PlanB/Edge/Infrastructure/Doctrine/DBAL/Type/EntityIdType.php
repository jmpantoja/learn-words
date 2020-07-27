<?php

declare(strict_types=1);

namespace PlanB\Edge\Infrastructure\Doctrine\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Domain\VarType\Exception\InvalidTypeException;


abstract class EntityIdType extends Type
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        if(is_scalar($value)){
            $value = EntityId::fromString($value);
        }

        if (!($value instanceof EntityId)) {
            throw new InvalidTypeException($value, EntityId::class);
        }

        return (string)$value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (empty($value)) {
            return null;
        }

        return $this->fromString($value);
    }


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
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * @inheritDoc
     */
    abstract public function getName();

    abstract protected function fromString(string $value): EntityId;
}
