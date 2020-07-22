<?php

namespace spec\LearnWords\Infrastructure\Domain\Word\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use LearnWords\Domain\Word\WordId;
use LearnWords\Infrastructure\Domain\Word\DBAL\WordIdType;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

class WordIdTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(WordIdType::class);
    }

    public function it_is_able_to_convert_from_string_to_wordId(AbstractPlatform $platform)
    {
        $uuid = Uuid::uuid1()->toString();

        $response = $this->convertToPHPValue($uuid, $platform);

        $response->shouldBeAnInstanceOf(WordId::class);
        $response->getUuid()->shouldReturn($uuid);
    }

    public function it_returns_null_when_try_to_convert_null_to_wordId(AbstractPlatform $platform)
    {
        $this->convertToPHPValue(null, $platform)
            ->shouldReturn(null);
    }

    public function it_has_the_correct_name(){
        $this->getName()->shouldReturn('WordId');
    }
}
