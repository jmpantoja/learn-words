<?php

namespace spec\LearnWords\Infrastructure\Domain\Word\Persister;

use League\Tactician\CommandBus;
use LearnWords\Application\Word\UseCase\DeleteTag;
use LearnWords\Application\Word\UseCase\DeleteWord;
use LearnWords\Application\Word\UseCase\SaveTag;
use LearnWords\Application\Word\UseCase\SaveWord;
use LearnWords\Domain\Word\Tag;
use LearnWords\Domain\Word\Word;
use LearnWords\Domain\Word\WordPersistence\WordHasBeenCreated;
use LearnWords\Infrastructure\Domain\Word\Persister\TagPersister;
use LearnWords\Infrastructure\Domain\Word\Persister\WordPersister;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use PlanB\Edge\Domain\Event\DomainEventsCollector;
use Prophecy\Argument;

class TagPersisterSpec extends ObjectBehavior
{
    public function let(CommandBus $commandBus)
    {
        $this->beConstructedWith($commandBus);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TagPersister::class);
    }

    public function it_detects_if_supports_an_object(Tag $tag)
    {
        $this->supports($tag)->shouldReturn(true);
    }

    public function it_detects_if_not_supports_an_object()
    {
        $this->supports($this)->shouldReturn(false);
    }

    public function it_is_able_to_persist_a_word(Tag $tag,
                                                 CommandBus $commandBus)
    {
        $this->persist($tag, $context = []);

        $commandBus->handle(Argument::type(SaveTag::class))
            ->shouldBeCalledOnce();
    }

    public function it_is_able_to_delete_a_word(Tag $tag, CommandBus $commandBus)
    {
        $this->remove($tag, $context = []);

        $commandBus->handle(Argument::type(DeleteTag::class))
            ->shouldBeCalledOnce();
    }
}
