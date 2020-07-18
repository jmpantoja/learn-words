<?php

namespace spec\PlanB\Edge\Domain\Entity;

use ArrayObject;
use DateTimeImmutable;
use DateTimeInterface;
use Error;
use Exception;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Domain\Entity\Event;
use PlanB\Edge\Domain\Event\DomainEvent;
use Ramsey\Uuid\Uuid;
use Throwable;

class EventSpec extends ObjectBehavior
{
    const UUID = '3fb70c94-54da-485f-8bcf-12924876704e';

    public function let(DateTimeImmutable $date)
    {
        $this->beConstructedWith(DomainEvent::class, 'eventData', $date);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Event::class);
    }

    public function it_throw_an_expcetion_when_access_id_before_of_persist_the_object(){

        $this->shouldThrow(Error::class)
            ->during('getId');

    }

    public function it_has_the_right_name()
    {
        $this->getName()->shouldReturn('PlanB.Domain_Event');
    }

    public function it_has_the_right_event_data()
    {
        $this->getEvent()->shouldReturn('eventData');
    }

    public function it_has_the_right_date(DateTimeImmutable $date)
    {
        $this->getDate()->shouldReturn($date);
    }
}

