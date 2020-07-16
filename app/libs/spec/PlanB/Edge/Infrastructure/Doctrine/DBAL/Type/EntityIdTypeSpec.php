<?php

namespace spec\PlanB\Edge\Infrastructure\Doctrine\DBAL\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Infrastructure\Doctrine\DBAL\Type\EntityIdType;
use PlanB\Edge\Shared\Exception\InvalidTypeException;
use Prophecy\Argument;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use stdClass;

class EntityIdTypeSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ConcreteEntityIdType::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(EntityIdType::class);
    }

    public function it_is_able_to_converts_a_null_value_to_database_representation(AbstractPlatform $abstractPlatform)
    {

        $this->convertToDatabaseValue(null, $abstractPlatform)
            ->shouldReturn(null);
    }

    public function it_is_able_to_converts_a_string_value_to_database_representation(AbstractPlatform $abstractPlatform)
    {
        $uuid = Uuid::uuid4()->toString();

        $this->convertToDatabaseValue($uuid, $abstractPlatform)
            ->shouldReturn($uuid);
    }

    public function it_throws_an_exception_when_try_to_convert_an_invalid_value(AbstractPlatform $abstractPlatform)
    {
        $uuid = 'invalid uuid';

        $this->shouldThrow(InvalidUuidStringException::class)
            ->during('convertToDatabaseValue', [$uuid, $abstractPlatform]);
    }

    public function it_throws_an_exception_when_try_to_convert_an_invalid_type(AbstractPlatform $abstractPlatform)
    {
        $uuid = new stdClass();

        $this->shouldThrow(InvalidTypeException::class)
            ->during('convertToDatabaseValue', [$uuid, $abstractPlatform]);
    }


    public function it_is_able_to_converts_an_entityId_to_database_representation(EntityId $entityId,
                                                                                  AbstractPlatform $abstractPlatform)
    {
        $entityId->__toString()->willReturn('uuid');

        $this->convertToDatabaseValue($entityId, $abstractPlatform)
            ->shouldReturn('uuid');
    }

    public function it_is_able_to_converts_a_database_value_to_an_entityId(EntityId $entityId,
                                                                                  AbstractPlatform $abstractPlatform)
    {
        $this->convertToPHPValue(null, $abstractPlatform)
            ->shouldReturn(null);

        $this->convertToPHPValue('valor en bbdd', $abstractPlatform)
            ->shouldBeAnInstanceOf(EntityId::class);
    }

    public function it_returns_sql_declaration(AbstractPlatform $abstractPlatform)
    {
        $abstractPlatform->getGuidTypeDeclarationSQL([])->willReturn('CHAR(36)');

        $this->getSQLDeclaration([], $abstractPlatform)
            ->shouldReturn('CHAR(36)');
    }
}

class ConcreteEntityIdType extends EntityIdType
{

    /**
     * @inheritDoc
     */
    public function getName()
    {
        // TODO: Implement getName() method.
    }

    protected function fromString(string $value): EntityId
    {
        return  new EntityId();
    }
}
