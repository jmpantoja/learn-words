<?php

namespace spec\LearnWords\Infrastructure\Domain\Dictionary\Persister;

use League\Tactician\CommandBus;
use LearnWords\Application\Dictionary\UseCase\DeleteTag;
use LearnWords\Application\Dictionary\UseCase\SaveTag;
use LearnWords\Domain\Dictionary\Tag;
use LearnWords\Infrastructure\Domain\Dictionary\Persister\TagPersister;
use PhpSpec\ObjectBehavior;
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

    public function it_is_able_to_persist_a_tag(Tag $tag,
                                                CommandBus $commandBus)
    {
        $this->persist($tag);

        $commandBus->handle(Argument::type(SaveTag::class))
            ->shouldBeCalledOnce();
    }

    public function it_is_able_to_delete_a_tag(Tag $tag, CommandBus $commandBus)
    {
        $this->remove($tag);

        $commandBus->handle(Argument::type(DeleteTag::class))
            ->shouldBeCalledOnce();
    }
}
