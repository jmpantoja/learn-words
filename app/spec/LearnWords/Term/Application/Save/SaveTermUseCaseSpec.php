<?php

namespace spec\LearnWords\Term\Application\Save;

use LearnWords\Term\Application\Save\SaveTerm;
use LearnWords\Term\Application\Save\SaveTermUseCase;
use LearnWords\Term\Domain\Model\Term;
use LearnWords\Term\Domain\Model\TermHasBeenCreated;
use LearnWords\Term\Domain\Model\Word;
use LearnWords\Term\Domain\Repository\TermRepositoryInterface;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\EventDispatcherForTesting;
use PlanB\Edge\Shared\Invokable;
use Prophecy\Argument;

class SaveTermUseCaseSpec extends ObjectBehavior
{
    public function let(TermRepositoryInterface $termRepository, Invokable $listener)
    {
        $this->beConstructedWith($termRepository);

        EventDispatcherForTesting::getInstance()
            ->addGlobalListener($listener->getWrappedObject());
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SaveTermUseCase::class);
    }

    public function it_is_able_to_save_a_term(TermRepositoryInterface $termRepository, Invokable $listener)
    {
        $command = SaveTerm::make([
            'word' => Word::spanish('hola')
        ]);

        $this->handle($command);
        $termRepository->persist(Argument::type(Term::class))->shouldHaveBeenCalled();

        $listener->__invoke(Argument::type(TermHasBeenCreated::class), Argument::any(), Argument::any())
            ->shouldHaveBeenCalledOnce();

        $listener->__invoke(Argument::any(), Argument::any(), Argument::any())
            ->shouldHaveBeenCalledTimes(1);
    }

}


class Callback
{
    public function __invoke()
    {
        dump('hola');
    }
}
