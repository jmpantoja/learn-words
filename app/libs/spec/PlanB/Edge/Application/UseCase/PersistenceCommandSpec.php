<?php

namespace spec\PlanB\Edge\Application\UseCase;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Application\UseCase\PersistenceCommand;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Domain\Entity\EntityInterface;

class PersistenceCommandSpec extends ObjectBehavior
{
    public function let(EntityInterface $entity)
    {
        $this->beAnInstanceOf(ConcretePersistenceCommand::class);
        $this->beConstructedThrough('make', [[], $entity]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(PersistenceCommand::class);
    }

    public function it_return_entity(EntityInterface $entity)
    {
        $this->entity()->shouldBeEqualTo($entity);
    }

    public function it_build_a_new_entity_instance()
    {
        $this->beConstructedThrough('make', []);
        $this->entity()->shouldHaveType(ConcreteEntity::class);
    }

    public function it_set_passed_data()
    {
        $this->beConstructedThrough('make', [[
            'name' => 'pepe',
            'lastName' => 'lopez',
        ]]);

        $this->name->shouldReturn('pepe');
        $this->lastName()->shouldReturn('lopez');
    }
}

class ConcretePersistenceCommand extends PersistenceCommand
{

    public ?string $name = null;
    private string $lastName = 'garcia';

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return string
     */
    public function lastName(): string
    {
        return $this->lastName;
    }

    protected function newInstance(): EntityInterface
    {
        return new ConcreteEntity();
    }
}

class ConcreteEntity implements EntityInterface
{

    public function id(): ?EntityId
    {
        return null;
    }
}
