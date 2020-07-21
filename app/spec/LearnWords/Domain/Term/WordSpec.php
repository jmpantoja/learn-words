<?php

namespace spec\LearnWords\Domain\Term;

use LearnWords\Domain\Term\Lang;
use LearnWords\Domain\Term\Word;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Validator\Exception\ValidationFailedException;

class WordSpec extends ObjectBehavior
{

    public function let()
    {
        $this->beConstructedWith('palabra', Lang::SPANISH());
    }

    public function it_is_initializable()
    {
        $this->beConstructedWith('palabra', Lang::ENGLISH());
        $this->shouldHaveType(Word::class);
    }

    public function it_is_initializable_with_spanish_lang()
    {
        $this->beConstructedThrough('spanish', ['palabra']);

        $this->shouldHaveType(Word::class);
        $this->getLang()->shouldBeLike(Lang::SPANISH());
        $this->getWord()->shouldReturn('palabra');
    }

    public function it_is_initializable_with_english_lang()
    {
        $this->beConstructedThrough('english', ['word']);

        $this->shouldHaveType(Word::class);
        $this->getLang()->shouldBeLike(Lang::ENGLISH());
        $this->getWord()->shouldReturn('word');
    }

    public function it_is_able_to_determine_if_a_value_is_valid()
    {
        $this::isValid([
            'word' => 'palabra',
            'lang' => Lang::SPANISH()
        ])->shouldReturn(true);

        $this::isValid([
            'word' => 'pa',
            'lang' => Lang::SPANISH()
        ])->shouldReturn(false);

    }

    public function it_throws_an_exception_when_the_word_is_too_short()
    {
        $this->beConstructedThrough('english', ['xx']);
        $this->shouldThrow(ValidationFailedException::class)->duringInstantiation();
    }

    public function it_is_stringable()
    {
        $this->beConstructedThrough('english', ['oooo']);

        $this->__toString()->shouldReturn('oooo');
    }
}
