<?php

namespace spec\LearnWords\Application\Term\UseCase;

use LearnWords\Application\Term\UseCase\SaveTerm;
use LearnWords\Application\Term\UseCase\SaveTermUseCase;
use LearnWords\Domain\Tag\TagList;
use LearnWords\Domain\Term\SaveTerm\TermHasBeenCreated;
use LearnWords\Domain\Term\Term;
use LearnWords\Domain\Term\TermId;
use LearnWords\Domain\Term\TermRepositoryInterface;
use LearnWords\Domain\Term\Word;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use PlanB\Edge\Domain\Event\DomainEventsCollector;
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

    public function it_is_able_to_save_a_term( TermRepositoryInterface $termRepository, DomainEventsCollector $eventsCollector)
    {
        $term = new Term(new TermId(), Word::spanish('hola'), TagList::collect());

        $command = SaveTerm::make($term);
        $this->handle($command);
        $termRepository->persist($term)->shouldHaveBeenCalled();

        $eventsCollector->handle(Argument::type(TermHasBeenCreated::class), TermHasBeenCreated::class)
            ->shouldHaveBeenCalledOnce();
    }
}
