<?php

namespace spec\LearnWords\Term\Domain\Model;

use LearnWords\Term\Domain\Model\Lang;
use LearnWords\Term\Domain\Model\Word;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Validator\Exception\ValidationFailedException;

class WordSpec extends ObjectBehavior
{

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

    public function it_throws_an_exception_when_the_word_is_too_short()
    {
        $this->beConstructedThrough('english', ['xx']);
        $this->shouldThrow(ValidationFailedException::class)->duringInstantiation();
    }
}
