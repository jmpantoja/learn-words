<?php

namespace spec\LearnWords\Infrastructure\Domain\Term\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use LearnWords\Domain\Term\Lang;
use LearnWords\Infrastructure\Domain\Term\DBAL\LangType;
use PhpSpec\ObjectBehavior;

class LangTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(LangType::class);
    }

    public function it_is_able_to_convert_from_string_to_lang(AbstractPlatform $platform)
    {
        $response = $this->convertToPHPValue('SPANISH', $platform);

        $response->shouldBeAnInstanceOf(Lang::class);
        $response->shouldBeLike(Lang::SPANISH());
    }

    public function it_returns_null_when_try_to_convert_null_to_termId(AbstractPlatform $platform)
    {
        $this->convertToPHPValue(null, $platform)
            ->shouldReturn(null);
    }


    public function it_has_the_correct_name()
    {
        $this->getName()->shouldReturn('lang');
    }
}
