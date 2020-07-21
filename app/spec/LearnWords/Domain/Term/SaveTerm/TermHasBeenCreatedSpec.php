<?php

namespace spec\LearnWords\Domain\Term\SaveTerm;

use LearnWords\Domain\Term\SaveTerm\TermHasBeenCreated;
use LearnWords\Domain\Term\Term;
use PhpSpec\ObjectBehavior;

class TermHasBeenCreatedSpec extends ObjectBehavior
{
    public function let(Term $term)
    {
        $this->beConstructedWith($term);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TermHasBeenCreated::class);
    }

    public function it_returns_the_term_object(Term $term)
    {
        $this->getTerm()->shouldReturn($term);
    }
}
