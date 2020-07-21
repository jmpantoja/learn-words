<?php

namespace spec\LearnWords\Domain\Term;

use LearnWords\Domain\Term\Clue;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Validator\Exception\ValidationFailedException;

class ClueSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('frase con la pista');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Clue::class);
    }

    public function it_throws_an_exception_if_the_clue_is_too_short()
    {
        $this->beConstructedWith('pista');

        $this->shouldThrow(ValidationFailedException::class)
            ->duringInstantiation();

    }

    public function it_throws_an_exception_if_the_clue_contains_numbers()
    {
        $this->beConstructedWith('frase con la pista y numeros 8980');

        $this->shouldThrow(ValidationFailedException::class)
            ->duringInstantiation();

    }

    public function it_admit_punct_symbols()
    {
        $this->beConstructedWith('frase con la pista y, (signos) de puntuación.');

        $this->shouldNotThrow(ValidationFailedException::class)
            ->duringInstantiation();
    }

    public function it_retuns_the_clue_text(){
        $this->beConstructedWith('(FRASE CON LA PISTA.)');

        $this->getClue()
            ->shouldReturn('(frase con la pista.)');
    }
}
