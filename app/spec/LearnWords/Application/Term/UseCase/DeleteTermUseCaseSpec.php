<?php

namespace spec\LearnWords\Application\Term\UseCase;

use LearnWords\Application\Term\UseCase\DeleteTerm;
use LearnWords\Application\Term\UseCase\DeleteTermUseCase;
use LearnWords\Domain\Tag\TagList;
use LearnWords\Domain\Term\SaveTerm\TermHasBeenCreated;
use LearnWords\Domain\Term\Term;
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

    public function it_is_able_to_delete_a_term(Term $term,
                                                TermRepositoryInterface $termRepository,
                                                DomainEventsCollector $eventsCollector)
    {
        $command = new DeleteTerm($term->getWrappedObject());
        $this->handle($command);

        $termRepository->delete($term)->shouldBeCalled();
//
//        $eventsCollector->handle(Argument::type(TermHasBeenCreated::class), TermHasBeenCreated::class)
//            ->shouldHaveBeenCalledOnce();
    }
}
