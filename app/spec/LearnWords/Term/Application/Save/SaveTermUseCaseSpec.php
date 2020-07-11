<?php

namespace spec\LearnWords\Term\Application\Save;

use LearnWords\Term\Application\Save\SaveTerm;
use LearnWords\Term\Application\Save\SaveTermUseCase;
use LearnWords\Term\Domain\Model\Term;
use LearnWords\Term\Domain\Model\TermHasBeenCreated;
use LearnWords\Term\Domain\Model\Word;
use LearnWords\Term\Domain\Repository\TermRepositoryInterface;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEventsCollector;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use Prophecy\Argument;

class SaveTermUseCaseSpec extends ObjectBehavior
{
    public function let(TermRepositoryInterface $termRepository, DomainEventsCollector $eventsCollector)
    {
        $this->beConstructedWith($termRepository);

        DomainEventDispatcher::getInstance()
            ->setDomainEventsCollector($eventsCollector->getWrappedObject());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SaveTermUseCase::class);
    }

    public function it_is_able_to_save_a_term(TermRepositoryInterface $termRepository, DomainEventsCollector $eventsCollector)
    {
        $command = SaveTerm::make([
            'word' => Word::spanish('hola')
        ]);

        $this->handle($command);
        $termRepository->persist(Argument::type(Term::class))->shouldHaveBeenCalled();

        $eventsCollector->handle(Argument::type(TermHasBeenCreated::class), TermHasBeenCreated::class)
            ->shouldHaveBeenCalledOnce();
    }
}

