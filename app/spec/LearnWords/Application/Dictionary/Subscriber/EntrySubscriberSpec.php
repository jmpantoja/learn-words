<?php

namespace spec\LearnWords\Application\Dictionary\Subscriber;

use League\Tactician\CommandBus;
use LearnWords\Application\Dictionary\Subscriber\EntrySubscriber;
use LearnWords\Application\Dictionary\UseCase\SaveEntry;
use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\EntryPersistence\EntryHasBeenImported;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EntrySubscriberSpec extends ObjectBehavior
{
    public function let(CommandBus $commandBus){
        $this->beConstructedWith($commandBus);
    }

    private function getEventDispatcher(): EventDispatcher
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber($this->getWrappedObject());
        return $dispatcher;
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(EntrySubscriber::class);
    }

    public function it_is_able_to_handle_an_event(CommandBus $commandBus, Entry $entry){

        $event = new EntryHasBeenImported($entry->getWrappedObject());

        $dispatcher = $this->getEventDispatcher();
        $dispatcher->dispatch($event);

        $commandBus->handle(Argument::type(SaveEntry::class))->shouldBeCalledOnce();

    }


}
