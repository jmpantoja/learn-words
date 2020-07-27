<?php

namespace spec\PlanB\Edge\Domain\Collection;

use ArrayObject;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Collection\SnapshotList;
use PlanB\Edge\Domain\Collection\TypedList;

class SnapshotListSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedThrough('collect', [[
            'a', 'b', 'c'
        ]]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SnapshotList::class);
        $this->shouldHaveCount(3);
    }

    public function it_is_initializable_via_transversable()
    {
        $this->beConstructedThrough('collect', [new ArrayObject([
            'a', 'b', 'c'
        ])]);

        $this->shouldHaveType(SnapshotList::class);
        $this->shouldHaveCount(3);
    }

    public function it_is_initializable_via_collection()
    {
        $collection = new ArrayCollection(['a', 'b', 'c']);

        $this->beConstructedThrough('collect', [$collection]);
        $this->shouldHaveType(SnapshotList::class);
        $this->shouldHaveCount(3);
    }

    public function it_is_initializable_via_lazy_collection()
    {
        $collection = new ArrayCollection(['a', 'b', 'c']);
        $lazy = ConcreteLazyCollection::collect($collection);

        $this->beConstructedThrough('collect', [$lazy]);
        $this->shouldHaveType(SnapshotList::class);
        $this->shouldHaveCount(3);
    }

    public function it_is_able_to_recognize_inserts(Callback $callback)
    {
        $this->add('d');
        $this->set(4, 'e');

        $this->getUpdateDiff()->shouldHaveCount(0);
        $this->getDeleteDiff()->shouldHaveCount(0);

        $this->getInsertDiff()->shouldIterateAs([
            3 => 'd',
            4 => 'e'
        ]);

        $callback->__invoke('d', 3)->shouldBeCalledTimes(1);
        $callback->__invoke('e', 4)->shouldBeCalledTimes(1);
        $this->eachInsert($callback);
    }

    public function it_is_able_to_recognize_updates(Callback $callback)
    {
        $this[0] = 'd';
        $this->set(1, 'e');

        $this->getInsertDiff()->shouldHaveCount(0);
        $this->getDeleteDiff()->shouldHaveCount(0);


        $this->getUpdateDiff()->shouldIterateAs([
            0 => 'd',
            1 => 'e'
        ]);

        $callback->__invoke('d', 0)->shouldBeCalledTimes(1);
        $callback->__invoke('e', 1)->shouldBeCalledTimes(1);
        $this->eachUpdate($callback);
    }

    public function it_is_able_to_recognize_deletes(Callback $callback)
    {
        unset($this[0]);
        $this->removeElement('b');

        $this->getInsertDiff()->shouldHaveCount(0);
        $this->getUpdateDiff()->shouldHaveCount(0);

        $this->getDeleteDiff()->shouldIterateAs([
            0 => 'a',
            1 => 'b'
        ]);

        $callback->__invoke('a', 0)->shouldBeCalledTimes(1);
        $callback->__invoke('b', 1)->shouldBeCalledTimes(1);

        $this->eachDelete($callback);
    }

    public function it_clones_correctly(){
        $this->clone()->shouldNotReturn($this);
        $this->clone()->shouldBeLike($this);

        $this->clone()->getIterator()->shouldNotReturn($this->getIterator());
        $this->clone()->getIterator()->shouldBeLike($this->getIterator());
    }

}

class Callback
{
    public function __invoke()
    {

    }
}

class ConcreteLazyCollection extends TypedList
{

    public function getType(): string
    {
        return 'string';
    }
}
