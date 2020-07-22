<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Normalizer;

use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Symfony\Normalizer\CollectionNormalizer;
use Prophecy\Argument;

class CollectionNormalizerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(CollectionNormalizer::class);
    }

    public function it_supports_collections(Collection $collection)
    {
        $this->supportsNormalization($collection)->shouldReturn(true);
    }

    public function it_does_not_supports_other_classes()
    {
        $this->supportsNormalization(Argument::not(Collection::class))
            ->shouldReturn(false);
    }

    public function it_is_able_to_normalize_a_collection(Collection $collection)
    {
        $response = [
            'key' => 'value'
        ];

        $collection->toArray()->willReturn($response);

        $this->normalize($collection)->shouldReturn($response);
    }
}
