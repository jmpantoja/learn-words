<?php

namespace spec\LearnWords\Infrastructure\Domain\Word\Persister;

use League\Tactician\CommandBus;
use LearnWords\Application\Word\UseCase\DeleteWord;
use LearnWords\Application\Word\UseCase\SaveWord;
use LearnWords\Domain\Word\Word;
use LearnWords\Domain\Word\WordPersistence\WordHasBeenCreated;
use LearnWords\Infrastructure\Domain\Word\Persister\WordPersister;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use PlanB\Edge\Domain\Event\DomainEventsCollector;
use Prophecy\Argument;

class WordPersisterSpec extends ObjectBehavior
{
    public function let(CommandBus $commandBus)
    {
        $this->beConstructedWith($commandBus);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(WordPersister::class);
    }

    public function it_detects_if_supports_an_object(Word $word)
    {
        $this->supports($word)->shouldReturn(true);
    }

    public function it_detects_if_not_supports_an_object()
    {
        $this->supports($this)->shouldReturn(false);
    }

    public function it_is_able_to_persist_a_word(Word $word,
                                                 CommandBus $commandBus)
    {
        $this->persist($word, $context = []);

        $commandBus->handle(Argument::type(SaveWord::class))
            ->shouldBeCalledOnce();
    }

    public function it_is_able_to_delete_a_word(Word $word, CommandBus $commandBus)
    {
        $this->remove($word, $context = []);

        $commandBus->handle(Argument::type(DeleteWord::class))
            ->shouldBeCalledOnce();
    }
}
