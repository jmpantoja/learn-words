<?php

namespace spec\PlanB\Edge\Domain\Event;

use Carbon\CarbonImmutable;
use DateTimeInterface;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEvent;

class DomainEventSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ConcreteDomainEvent::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DomainEvent::class);
    }

    public function it_has_current_datetime_by_default()
    {

        $this->when()->getTimestamp()
            ->shouldReturn(CarbonImmutable::now()->getTimestamp());
    }

    public function it_can_be_initialized_with_a_custom_datatime()
    {

        $date = CarbonImmutable::create(2020, 07, 9, 12, 40, 30);
        $this->beConstructedWith($date);

        $this->when()->getTimestamp()
            ->shouldReturn($date->getTimestamp());
    }
}

class ConcreteDomainEvent extends DomainEvent
{
    public function __construct(DateTimeInterface $when = null)
    {
        parent::__construct($when);
    }
}
