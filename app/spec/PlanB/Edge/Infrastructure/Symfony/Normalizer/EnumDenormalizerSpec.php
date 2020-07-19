<?php

namespace spec\PlanB\Edge\Infrastructure\Symfony\Normalizer;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Enum\Enum;
use PlanB\Edge\Infrastructure\Symfony\Normalizer\EnumDenormalizer;

class EnumDenormalizerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(EnumDenormalizer::class);
    }

    public function it_supports_enum_type()
    {
        $this->supportsDenormalization(null, ConcreteEnum::class)
            ->shouldReturn(true);
    }

    public function it_does_not_supports_other_type()
    {
        $this->supportsDenormalization(null, self::class)
            ->shouldReturn(false);
    }

    public function it_is_able_to_denormalize_a_value()
    {
        $this->denormalize('a', ConcreteEnum::class)
        ->shouldBeLike(ConcreteEnum::A());
    }
}


class ConcreteEnum extends Enum
{
    private const A = 'a';
    private const B = 'ba';

}
