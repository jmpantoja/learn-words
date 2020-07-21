<?php

namespace spec\LearnWords\Infrastructure\Domain\Term\Persister;

use League\Tactician\CommandBus;
use LearnWords\Application\Term\UseCase\DeleteTerm;
use LearnWords\Application\Term\UseCase\SaveTerm;
use LearnWords\Domain\Term\Term;
use LearnWords\Infrastructure\Domain\Term\Persister\TermPersister;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TermPersisterSpec extends ObjectBehavior
{
    public function let(CommandBus $commandBus)
    {
        $this->beConstructedWith($commandBus);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TermPersister::class);
    }

    public function it_detects_if_supports_an_object(Term $term)
    {
        $this->supports($term)->shouldReturn(true);
    }

    public function it_detects_if_not_supports_an_object(Term $term)
    {
        $this->supports($this)->shouldReturn(false);
    }

    public function it_is_able_to_persist_a_term(Term $term, CommandBus $commandBus)
    {
        $this->persist($term, $context = []);

        $commandBus->handle(Argument::type(SaveTerm::class))
            ->shouldBeCalledOnce();
    }

    public function it_is_able_to_delete_a_term(Term $term, CommandBus $commandBus)
    {
        $this->remove($term, $context = []);

        $commandBus->handle(Argument::type(DeleteTerm::class))
            ->shouldBeCalledOnce();
    }
}
