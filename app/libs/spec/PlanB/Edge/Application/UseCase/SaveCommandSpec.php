<?php

namespace spec\PlanB\Edge\Application\UseCase;

use PhpSpec\ObjectBehavior;
use PlanB\Edge\Application\UseCase\SaveCommand;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Domain\Entity\EntityInterface;

class SaveCommandSpec extends ObjectBehavior
{
    public function let(EntityInterface $entity)
    {
        $this->beAnInstanceOf(ConcreteSaveCommand::class);
        $this->beConstructedThrough('make', [[
            'name' => 'pepe'
        ], $entity]);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(SaveCommand::class);
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
        ]]);

        $this->entity()->name()->shouldReturn('pepe');
    }
}

class ConcreteSaveCommand extends SaveCommand
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

    protected function create(array $data): EntityInterface
    {
        return new ConcreteEntity(...[
            $data['name'] ?? ''
        ]);
    }

    protected function update(array $data, $entity = null): EntityInterface
    {
        return $entity->setName($data['name']);
    }
}

class ConcreteEntity implements EntityInterface
{

    private string $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function id(): ?EntityId
    {
        return null;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ConcreteEntity
     */
    public function setName(string $name): ConcreteEntity
    {
        $this->name = $name;
        return $this;
    }

}
