<?php

namespace spec\LearnWords\Application\Word\UseCase;

use LearnWords\Application\Word\UseCase\DeleteWord;
use LearnWords\Application\Word\UseCase\DeleteWordUseCase;
use LearnWords\Domain\Word\Word;
use LearnWords\Domain\Word\WordPersistence\WordHasBeenDeleted;
use LearnWords\Domain\Word\WordRepository;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use PlanB\Edge\Domain\Event\DomainEventsCollector;
use Prophecy\Argument;

class DeleteWordUseCaseSpec extends ObjectBehavior
{
    public function let(WordRepository $wordRepository)
    {
        $this->beConstructedWith($wordRepository);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DeleteWordUseCase::class);
    }

    public function it_is_able_to_delete_a_word(Word $word,
                                                WordRepository $wordRepository)
    {
        $command = new DeleteWord($word->getWrappedObject());
        $this->handle($command);

        $wordRepository->delete($word)->shouldBeCalled();
    }
}
