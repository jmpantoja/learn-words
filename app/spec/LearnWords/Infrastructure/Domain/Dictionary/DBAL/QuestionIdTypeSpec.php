<?php

namespace spec\LearnWords\Infrastructure\Domain\Dictionary\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use LearnWords\Domain\Dictionary\EntryId;
use LearnWords\Domain\Dictionary\QuestionId;
use LearnWords\Infrastructure\Domain\Dictionary\DBAL\QuestionIdType;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

class QuestionIdTypeSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(QuestionIdType::class);
    }

    public function it_is_able_to_convert_from_string_to_questionId(AbstractPlatform $platform)
    {
        $uuid = Uuid::uuid1()->toString();

        $response = $this->convertToPHPValue($uuid, $platform);

        $response->shouldBeAnInstanceOf(QuestionId::class);
        $response->getUuid()->shouldReturn($uuid);
    }

    public function it_returns_null_when_try_to_convert_null_to_questionId(AbstractPlatform $platform)
    {
        $this->convertToPHPValue(null, $platform)
            ->shouldReturn(null);
    }

    public function it_has_the_correct_name()
    {
        $this->getName()->shouldReturn('QuestionId');
    }
}
