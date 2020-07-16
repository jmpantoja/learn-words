<?php

namespace spec\PlanB\Edge\Domain\Entity;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Entity\EntityId;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class EntityIdSpec extends ObjectBehavior
{
    const UUID = '3fb70c94-54da-485f-8bcf-12924876704e';


    public function it_is_initializable()
    {
        $this->shouldHaveType(EntityId::class);
    }

    public function it_returns_an_uuid_by_default()
    {
        $this->getUuid()->shouldBeString();
    }


    public function it_is_able_to_determine_if_two_entity_id_are_equal()
    {
        $uuid = Uuid::uuid4();
        $equalId = new EntityId($uuid);

        $this->beConstructedWith($uuid->toString());
        $this->equals($equalId)->shouldBe(true);
    }

    public function it_is_able_to_determine_if_two_entity_id_are_different()
    {
        $other = Uuid::uuid4();
        $otherId = new EntityId($other);

        $uuid = Uuid::uuid4();
        $this->beConstructedWith($uuid->toString());


        $this->equals($otherId)->shouldBe(false);
    }

    public function it_can_be_transformed_to_a_string()
    {

        $uuid = Uuid::uuid4();
        $this->beConstructedWith($uuid->toString());

        $this->__toString()->shouldBe($uuid->toString());
    }
}

