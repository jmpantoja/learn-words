<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Serializer\Normalizer;

use Carbon\CarbonImmutable;
use DateTimeInterface;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Domain\Event\DomainEvent;
use PlanB\Edge\Infrastructure\Symfony\Serializer\Normalizer\DomainEventNormalizer;

class DomainEventNormalizerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(DomainEventNormalizer::class);
    }

    public function it_is_able_to_normalize_a_domain_event()
    {
        $entityId = new EntityId();
        $name = 'pepe lopez';
        $date = CarbonImmutable::now();

        $event = new ConcreteDomainEventInterface(...[
            $entityId,
            'pepe lopez',
            $date
        ]);

        $response = $this->normalize($event);

        $response->shouldReturn([
            'id' => (string)$entityId,
            'name' => (string)$name,
        ]);

        $response->shouldNotHaveKey('when');
    }
}

class ConcreteDomainEventInterface extends DomainEvent
{
    private EntityId $id;
    private string $name;

    public function __construct(EntityId $id, string $name, DateTimeInterface $when)
    {
        $this->id = $id;
        $this->name = $name;
        parent::__construct($when);
    }
}
