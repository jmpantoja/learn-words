<?php

namespace spec\LearnWords\Infrastructure\Domain\Dictionary\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use LearnWords\Domain\Dictionary\Lang;
use LearnWords\Infrastructure\Domain\Dictionary\DBAL\LangType;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

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


    public function it_is_able_to_convert_from_to_string(AbstractPlatform $platform)
    {
        $response = $this->convertToDatabaseValue(Lang::SPANISH(), $platform);

        $response->shouldReturn('SPANISH');
    }

    public function it_returns_null_when_try_to_convert_null_to_wordId(AbstractPlatform $platform)
    {
        $this->convertToPHPValue('SPANISH', $platform)
            ->shouldBeLike(Lang::SPANISH());
    }

    public function it_returns_the_right_sql_declaration(AbstractPlatform $platform){

        $fieldDeclaration = [];

        $platform->getVarcharTypeDeclarationSQL($fieldDeclaration)->willReturn('declaration');
        $this->getSQLDeclaration($fieldDeclaration, $platform)->shouldReturn('declaration');



    }


    public function it_has_the_correct_name()
    {
        $this->getName()->shouldReturn('lang');
    }
}
