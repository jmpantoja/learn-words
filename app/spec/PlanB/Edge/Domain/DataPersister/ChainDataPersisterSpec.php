<?php

namespace spec\PlanB\Edge\Domain\DataPersister;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\DataPersister\ChainDataPersister;
use PlanB\Edge\Domain\DataPersister\DataPersisterInterface;
use Prophecy\Argument;

class ChainDataPersisterSpec extends ObjectBehavior
{
    public function let(DataPersisterInterface $persisterClassA,
                        DataPersisterInterface $persisterClassB)
    {
        $persisterClassA->supports(Argument::any())->willReturn(false);
        $persisterClassB->supports(Argument::any())->willReturn(false);

        $persisterClassA->supports('ClassA')->willReturn(true);
        $persisterClassB->supports('ClassB')->willReturn(true);

        $this->beConstructedWith($persisterClassA, $persisterClassB);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ChainDataPersister::class);
    }

    public function it_delegate_persist_operation_in_the_right_perister(DataPersisterInterface $persisterClassA,
                                                                        DataPersisterInterface $persisterClassB)
    {
        $this->persist('ClassA');

        $persisterClassA->persist('ClassA')->shouldBeCalledOnce();
        $persisterClassB->persist('ClassA')->shouldNotBeCalled();
    }

    public function it_delegate_remove_operation_in_the_right_perister(DataPersisterInterface $persisterClassA,
                                                                       DataPersisterInterface $persisterClassB)
    {
        $this->remove('ClassB');

        $persisterClassB->remove('ClassB')->shouldBeCalledOnce();
        $persisterClassA->remove('ClassB')->shouldNotBeCalled();
    }

    public function it_do_nothing_when_there_is_not_a_right_persister(DataPersisterInterface $persisterClassA,
                                                                      DataPersisterInterface $persisterClassB)
    {
        $this->persist('ClassC');
        $persisterClassB->persist('ClassC')->shouldNotBeCalled();
        $persisterClassA->persist('ClassC')->shouldNotBeCalled();

        $this->remove('ClassC');
        $persisterClassB->remove('ClassC')->shouldNotBeCalled();
        $persisterClassA->remove('ClassC')->shouldNotBeCalled();
    }

    public function it_let_us_know_if_a_data_is_supported()
    {

        $this->supports('ClassA')->shouldReturn(true);
        $this->supports('ClassB')->shouldReturn(true);
        $this->supports('ClassC')->shouldReturn(false);

    }
}
