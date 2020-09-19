<?php

namespace spec\LearnWords\Application\Dictionary\UseCase;

use LearnWords\Application\Dictionary\UseCase\DeleteEntry;
use LearnWords\Application\Dictionary\UseCase\DeleteEntryUseCase;
use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\EntryRepository;
use PhpSpec\ObjectBehavior;

class DeleteEntryUseCaseSpec extends ObjectBehavior
{
    public function let(EntryRepository $entryRepository)
    {
        $this->beConstructedWith($entryRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DeleteEntryUseCase::class);
    }

    public function it_is_able_to_delete_an_entry(Entry $entry,
                                                EntryRepository $entryRepository)
    {
        $command = new DeleteEntry($entry->getWrappedObject());
        $this->handle($command);

        $entryRepository->delete($entry->getWrappedObject())->shouldBeCalled();
    }
}
