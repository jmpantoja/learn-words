<?php

namespace spec\LearnWords\Application\Word\UseCase;

use LearnWords\Application\Word\UseCase\SaveWord;
use LearnWords\Application\Word\UseCase\SaveWordUseCase;
use LearnWords\Domain\Word\Lang;
use LearnWords\Domain\Word\TagList;
use LearnWords\Domain\Word\Word;
use LearnWords\Domain\Word\WordPersistence\WordHasBeenCreated;
use LearnWords\Domain\Word\WordRepository;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use PlanB\Edge\Domain\Event\DomainEventsCollector;
use Prophecy\Argument;

class SaveWordUseCaseSpec extends ObjectBehavior
{
    public function let(WordRepository $wordRepository, DomainEventsCollector $eventsCollector)
    {
        $eventsCollector->handle(Argument::any(), Argument::any())->willReturn($eventsCollector);

        $this->beConstructedWith($wordRepository);

        DomainEventDispatcher::getInstance()
            ->setDomainEventsCollector($eventsCollector->getWrappedObject());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SaveWordUseCase::class);
    }

    public function it_is_able_to_save_a_word(WordRepository $wordRepository,
                                              DomainEventsCollector $eventsCollector)
    {
        $word = new Word('hola', Lang::SPANISH(), TagList::empty());

        $command = new SaveWord($word);
        $this->handle($command);
        $wordRepository->persist($word)->shouldHaveBeenCalledOnce();

        $eventsCollector->handle(Argument::type(WordHasBeenCreated::class), WordHasBeenCreated::class)
            ->shouldHaveBeenCalledOnce();
    }
}

