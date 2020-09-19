<?php

namespace spec\LearnWords\Infrastructure\Domain\Dictionary\Persister;

use League\Tactician\CommandBus;
use LearnWords\Application\Dictionary\UseCase\DeleteEntry;
use LearnWords\Application\Dictionary\UseCase\SaveEntry;
use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Infrastructure\Domain\Dictionary\Persister\EntryPersister;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntryPersisterSpec extends ObjectBehavior
{
    public function let(CommandBus $commandBus)
    {
        $this->beConstructedWith($commandBus);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(EntryPersister::class);
    }

    public function it_detects_if_supports_an_object(Entry $entry)
    {
        $this->supports($entry)->shouldReturn(true);
    }

    public function it_detects_if_not_supports_an_object()
    {
        $this->supports($this)->shouldReturn(false);
    }

    public function it_is_able_to_persist_an_entry(Entry $entry,
                                                CommandBus $commandBus)
    {
        $this->persist($entry);

        $commandBus->handle(Argument::type(SaveEntry::class))
            ->shouldBeCalledOnce();
    }

    public function it_is_able_to_delete_an_entry(Entry $entry, CommandBus $commandBus)
    {
        $this->remove($entry);

        $commandBus->handle(Argument::type(DeleteEntry::class))
            ->shouldBeCalledOnce();
    }

}
