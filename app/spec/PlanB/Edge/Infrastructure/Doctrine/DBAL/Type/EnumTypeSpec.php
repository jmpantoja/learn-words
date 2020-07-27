<?php

namespace spec\PlanB\Edge\Infrastructure\Doctrine\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Enum\Enum;
use PlanB\Edge\Domain\VarType\Exception\InvalidTypeException;
use PlanB\Edge\Infrastructure\Doctrine\DBAL\Type\EnumType;
use Prophecy\Argument;

class EnumTypeSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ConcreteEnumType::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(EnumType::class);
    }

    public function it_is_able_to_convert_a_enum_to_database_value(Enum $enum, AbstractPlatform $platform)
    {
        $enum->getKey()->willReturn('enum-key');
        $this->convertToDatabaseValue($enum, $platform)->shouldReturn('enum-key');
    }

    public function it_is_able_to_convert_null_to_database_value_null(Enum $enum, AbstractPlatform $platform)
    {
        $this->convertToDatabaseValue(null, $platform)->shouldBeNull();
    }

    public function it_throws_an_exception_if_value_is_not_an_enum($enum, AbstractPlatform $platform)
    {
        $this->shouldThrow(InvalidTypeException::class)
            ->duringConvertToDatabaseValue($enum, $platform);
    }

    public function it_is_able_to_convert_a_database_value_to_enum(Enum $enum, AbstractPlatform $platform)
    {
        $this->convertToPHPValue('KEY', $platform)
            ->shouldBeLike(ConcreteEnum::KEY());
    }

    public function it_convert_a_database_null_to_null(Enum $enum, AbstractPlatform $platform)
    {
        $this->convertToPHPValue(null, $platform)
            ->shouldBeNull();
    }

    public function it_returns_sql_declaration(AbstractPlatform $abstractPlatform)
    {

        $abstractPlatform
            ->getVarcharTypeDeclarationSQL(Argument::any())
            ->willReturn('VARCHAR(255)');

        $this->getSQLDeclaration([], $abstractPlatform)
            ->shouldReturn('VARCHAR(255)');
    }
}

class ConcreteEnumType extends EnumType
{

    /**
     * @inheritDoc
     */
    public function byKey(string $value, AbstractPlatform $platform): Enum
    {
        return ConcreteEnum::byKey($value);
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return 'name';
    }
}

class ConcreteEnum extends Enum
{
    private const KEY = 'value';

}
