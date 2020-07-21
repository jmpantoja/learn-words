<?php

namespace spec\LearnWords\Application\Term\UseCase;

use LearnWords\Application\Term\UseCase\DeleteTerm;
use LearnWords\Application\Term\UseCase\DeleteTermUseCase;
use LearnWords\Application\Term\UseCase\SaveTerm;
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

class DeleteTermUseCaseSpec extends ObjectBehavior
{
    public function let(TermRepositoryInterface $termRepository, DomainEventsCollector $eventsCollector)
    {
        $eventsCollector->handle(Argument::any(), Argument::any())->willReturn($eventsCollector);

        $this->beConstructedWith($termRepository);

        DomainEventDispatcher::getInstance()
            ->setDomainEventsCollector($eventsCollector->getWrappedObject());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(DeleteTermUseCase::class);
    }

    public function it_is_able_to_delete_a_term( TermRepositoryInterface $termRepository, DomainEventsCollector $eventsCollector)
    {
        $term = new Term(new TermId(), Word::spanish('hola'), TagList::collect());

        $command = new DeleteTerm($term);
        $this->handle($command);

        $termRepository->delete($term)->shouldHaveBeenCalledOnce();
//
//        $eventsCollector->handle(Argument::type(TermHasBeenCreated::class), TermHasBeenCreated::class)
//            ->shouldHaveBeenCalledOnce();
    }
}
