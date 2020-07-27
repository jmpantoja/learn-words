<?php

namespace spec\PlanB\Edge\Domain\Collection;

use ArrayIterator;
use Doctrine\Common\Collections\AbstractLazyCollection;
use Doctrine\Common\Collections\Collection;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Collection\Exception\InvalidCollectionElement;
use PlanB\Edge\Domain\Collection\TypedList;
use Traversable;

class TypedListSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ConcreteStringList::class);
        $this->beConstructedThrough('empty');
    }

    public function it_is_initializable_without_values()
    {
        $this->shouldHaveType(TypedList::class);
        $this->shouldHaveType(AbstractLazyCollection::class);
    }

    public function it_is_initializable_with_a_collection(Collection $input)
    {
        $this->beConstructedThrough('collect', [$input]);

        $this->shouldHaveType(TypedList::class);
        $this->shouldHaveType(AbstractLazyCollection::class);
    }

    public function it_is_initializable_with_a_traversable(Traversable $input)
    {
        $this->beConstructedThrough('collect', [$input]);

        $this->shouldHaveType(TypedList::class);
        $this->shouldHaveType(AbstractLazyCollection::class);
    }

    public function it_throws_an_exception_when_initialize_with_invalid_values()
    {
        $this->beConstructedThrough('collect', [new ArrayIterator([123, 456])]);

        $this->shouldThrow(InvalidCollectionElement::class)->duringInstantiation();
    }


    public function it_is_able_to_add_values()
    {
        $this->add('valor');
        $this->count()->shouldReturn(1);

        $this->add('otro valor');
        $this->count()->shouldReturn(2);
    }

    public function it_is_able_to_set_values()
    {
        $this->set(10, 'valor');
        $this->count()->shouldReturn(1);

        $this->set(20, 'otro valor');
        $this->count()->shouldReturn(2);

        $this[10]->shouldReturn('valor');
        $this[20]->shouldReturn('otro valor');
    }

    public function it_throws_an_exception_when_value_types_is_not_correct()
    {
        $this->shouldThrow(InvalidCollectionElement::class)
            ->during('add', [123]);
    }


    public function it_is_able_to_reduce_a_collection()
    {
        $this->add('A');
        $this->add('B');
        $this->add('C');

        $this->reduce(function (?string $carry, string $item) {
            return sprintf('%s%s', $carry, strtolower($item));
        })->shouldReturn('abc');
    }
}

class ConcreteStringList extends TypedList
{
    public function getType(): string
    {
        return 'string';
    }
}


