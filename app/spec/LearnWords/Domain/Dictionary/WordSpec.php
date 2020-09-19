<?php

namespace spec\LearnWords\Domain\Dictionary;

use LearnWords\Domain\Dictionary\Lang;
use LearnWords\Domain\Dictionary\Word;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Validator\Exception\ValidationException;

class WordSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('hello', Lang::ENGLISH());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Word::class);
        $this->getWord()->shouldReturn('hello');
        $this->getLang()->shouldBeLike(Lang::ENGLISH());

        $this->isEnglish()->shouldReturn(true);
        $this->isSpanish()->shouldReturn(false);
    }

    public function it_is_able_by_spanish_shorthand(){
        $this->beConstructedThrough('Spanish', ['hola']);

        $this->getWord()->shouldReturn('hola');
        $this->getLang()->shouldBeLike(Lang::SPANISH());
    }

    public function it_is_able_by_english_shorthand(){
        $this->beConstructedThrough('English', ['hello']);

        $this->getWord()->shouldReturn('hello');
        $this->getLang()->shouldBeLike(Lang::ENGLISH());
    }

    public function it_is_convertible_to_a_string()
    {
        $this->__toString()->shouldReturn('hello');
    }

    public function it_throws_an_exception_when_input_data_is_wrong()
    {
        $this->beConstructedWith('XX', Lang::ENGLISH());
        $this->shouldThrow(ValidationException::class)->duringInstantiation();
    }

    public function it_validate_correctely()
    {
        $violations = $this::validate([
            'word' => 'XX',
            'lang' => Lang::SPANISH()
        ]);

        $violations->count()->shouldReturn(1);
        $violations->get(0)->getMessage()->shouldReturn('This value is too short. It should have 3 characters or more.');
    }

}
