<?php

namespace spec\LearnWords\Application\Dictionary\UseCase;

use LearnWords\Application\Dictionary\UseCase\SaveEntry;
use LearnWords\Application\Dictionary\UseCase\SaveEntryUseCase;
use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\EntryPersistence\EntryHasBeenCreated;
use LearnWords\Domain\Dictionary\EntryRepository;
use LearnWords\Domain\Dictionary\Lang;
use LearnWords\Domain\Dictionary\TagList;
use LearnWords\Domain\Dictionary\Word;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use PlanB\Edge\Domain\Event\DomainEventsCollector;
use Prophecy\Argument;

class SaveEntryUseCaseSpec extends ObjectBehavior
{
    public function let(EntryRepository $entryRepository, DomainEventsCollector $eventsCollector)
    {
        $this->beConstructedWith($entryRepository);

        $eventsCollector->handle(Argument::any())->willReturn($eventsCollector);
        DomainEventDispatcher::getInstance()
            ->setDomainEventsCollector($eventsCollector->getWrappedObject());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SaveEntryUseCase::class);
    }

    public function it_is_able_to_save_an_entry(EntryRepository $entryRepository,
                                                DomainEventsCollector $eventsCollector)
    {

        $entry = new Entry(Word::English('word'), TagList::empty());

        $command = new SaveEntry($entry);
        $this->handle($command);

        $entryRepository->persist($entry)->shouldHaveBeenCalledOnce();
        $eventsCollector->handle(Argument::type(EntryHasBeenCreated::class))
            ->shouldHaveBeenCalledOnce();
    }
}

