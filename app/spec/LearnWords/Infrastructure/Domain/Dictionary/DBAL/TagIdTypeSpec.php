<?php

namespace spec\LearnWords\Infrastructure\Domain\Dictionary\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use LearnWords\Domain\Dictionary\TagId;
use LearnWords\Infrastructure\Domain\Dictionary\DBAL\TagIdType;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

class TagIdTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(TagIdType::class);
    }

    public function it_is_able_to_convert_from_string_to_tagId(AbstractPlatform $platform)
    {
        $uuid = Uuid::uuid1()->toString();

        $response = $this->convertToPHPValue($uuid, $platform);

        $response->shouldBeAnInstanceOf(TagId::class);
        $response->getUuid()->shouldReturn($uuid);
    }

    public function it_returns_null_when_try_to_convert_null_to_tagId(AbstractPlatform $platform)
    {
        $this->convertToPHPValue(null, $platform)
            ->shouldReturn(null);
    }

    public function it_has_the_correct_name(){
        $this->getName()->shouldReturn('TagId');
    }
}
