<?php

namespace spec\LearnWords\Infrastructure\Domain\Term\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use LearnWords\Domain\Term\TermId;
use LearnWords\Infrastructure\Domain\Term\DBAL\TermIdType;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

class TermIdTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TermIdType::class);
    }

    public function it_is_able_to_convert_from_string_to_termId(AbstractPlatform $platform)
    {
        $uuid = Uuid::uuid1()->toString();

        $response = $this->convertToPHPValue($uuid, $platform);

        $response->shouldBeAnInstanceOf(TermId::class);
        $response->getUuid()->shouldReturn($uuid);
    }

    public function it_returns_null_when_try_to_convert_null_to_termId(AbstractPlatform $platform)
    {
        $this->convertToPHPValue(null, $platform)
            ->shouldReturn(null);
    }

    public function it_has_the_correct_name(){
        $this->getName()->shouldReturn('TermId');
    }
}
