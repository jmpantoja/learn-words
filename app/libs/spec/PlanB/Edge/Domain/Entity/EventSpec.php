<?php

namespace spec\PlanB\Edge\Domain\Entity;

use ArrayObject;
use DateTimeInterface;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Domain\Entity\Event;
use PlanB\Edge\Domain\Event\DomainEvent;
use Ramsey\Uuid\Uuid;

class EventSpec extends ObjectBehavior
{
    const UUID = '3fb70c94-54da-485f-8bcf-12924876704e';

    public function let(Uuid $uuid)
    {
        $uuid->__toString()->willReturn(self::UUID);

        $domainEvent = new DomainEventExample(new EntityId($uuid->getWrappedObject()));
        $this->beConstructedWith($domainEvent);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Event::class);
    }

    public function it_has_the_right_name()
    {
        $this->name()->shouldReturn('PlanB.Domain_Event_Example');
    }

    public function it_has_the_right_event_data()
    {
        $this->eventAsArray()->shouldIterateAs([
            'id' => [
                'uuid' => self::UUID
            ],
            'entity' => [
                'name' => 'pepe',
                'lastName' => 'lopez',
            ]
        ]);
    }
}

class DomainEventExample extends DomainEvent
{
    private $id;
    private $entity;

    public function __construct(EntityId $id, DateTimeInterface $when = null)
    {
        $this->id = $id;
        $this->entity = new ArrayObject([
            'name' => 'pepe',
            'lastName' => 'lopez'
        ]);

        parent::__construct($when);
    }
}

