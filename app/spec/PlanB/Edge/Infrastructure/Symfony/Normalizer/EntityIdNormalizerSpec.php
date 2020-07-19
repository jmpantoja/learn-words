<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Normalizer;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Infrastructure\Symfony\Normalizer\EntityIdNormalizer;

class EntityIdNormalizerSpec extends ObjectBehavior
{

    public function it_is_initializable()
    {
        $this->shouldHaveType(EntityIdNormalizer::class);
    }

    public function it_supports_entityId_type(EntityId $entityId)
    {
        $this->supportsNormalization($entityId)
            ->shouldReturn(true);
    }

    public function it_does_not_supports_other_type()
    {
        $this->supportsNormalization(null,)
            ->shouldReturn(false);
    }

    public function it_is_able_to_denormalize_a_value(EntityId $entityId)
    {
        $uuid = 'uuid_value';
        $entityId->__toString()->willReturn($uuid);

        $this->normalize($entityId)
            ->shouldBeLike($uuid);
    }
}
