<?php

namespace spec\PlanB\Edge\Domain\Collection;

use ArrayIterator;
use Doctrine\Common\Collections\AbstractLazyCollection;
use Doctrine\Common\Collections\ArrayCollection;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Collection\EntityList;
use PlanB\Edge\Domain\Collection\Exception\InvalidClassnameException;
use PlanB\Edge\Domain\Collection\Exception\InvalidCollectionElement;
use PlanB\Edge\Domain\Collection\Exception\ValueIsNotAnEntityException;
use PlanB\Edge\Domain\Collection\TypedList;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Domain\Entity\EntityInterface;
use stdClass;

class EntityListSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ConcreteEntityList::class);
    }

    public function it_is_initializable_without_values()
    {
        $this->beConstructedThrough('empty');

        $this->shouldHaveType(EntityList::class);
        $this->shouldHaveType(TypedList::class);
        $this->shouldHaveType(AbstractLazyCollection::class);
    }

    public function it_is_initializable_with_an_array()
    {
        $this->beConstructedThrough('collect', [[
            new Entity(),
            new Entity(),
            new Entity(),
        ]]);

        $this->shouldHaveType(EntityList::class);
        $this->shouldHaveType(TypedList::class);
        $this->shouldHaveType(AbstractLazyCollection::class);

        $this->count()->shouldReturn(3);
    }

    public function it_is_initializable_with_an_iterator()
    {
        $this->beConstructedThrough('collect', [new ArrayIterator([
            new Entity(),
            new Entity(),
            new Entity(),
        ])]);

        $this->shouldHaveType(EntityList::class);
        $this->shouldHaveType(TypedList::class);
        $this->shouldHaveType(AbstractLazyCollection::class);

        $this->count()->shouldReturn(3);
    }

    public function it_throws_an_exception_when_add_a_not_an_entity_interface()
    {
        $this->beAnInstanceOf(ConcreteNotEntityList::class);
        $this->beConstructedThrough('collect', [[
            new stdClass()
        ]]);

        $this->shouldThrow(ValueIsNotAnEntityException::class)->duringInstantiation();
    }

    public function it_throws_an_exception_when_type_is_not_an_entity_interface()
    {
        $this->beAnInstanceOf(ConcreteNotEntityList::class);
        $this->beConstructedThrough('collect', [[
            new Entity()
        ]]);

        $this->shouldThrow(InvalidCollectionElement::class)->duringInstantiation();
    }

    public function it_throws_an_exception_when_type_is_not_a_class_name()
    {
        $this->beAnInstanceOf(ConcreteNotExistingTypeList::class);
        $this->beConstructedThrough('collect', [[
            new Entity()
        ]]);

        $this->shouldThrow(InvalidClassnameException::class)->duringInstantiation();
    }
}


class ConcreteNotExistingTypeList extends EntityList
{
    public function getType(): string
    {
        return 'xxxx';
    }
}


class ConcreteNotEntityList extends EntityList
{
    public function getType(): string
    {
        return stdClass::class;
    }
}

class ConcreteEntityList extends EntityList
{
    public function getType(): string
    {
        return Entity::class;
    }
}

class Entity implements EntityInterface
{

    /**
     * @var EntityId
     */
    private EntityId $id;

    public function __construct()
    {
        $this->id = new EntityId();
    }

    /**
     * @return EntityId
     */
    public function getId(): EntityId
    {
        return $this->id;
    }

}
