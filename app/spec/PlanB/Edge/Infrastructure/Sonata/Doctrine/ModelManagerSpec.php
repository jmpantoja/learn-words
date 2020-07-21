<?php

namespace spec\PlanB\Edge\Infrastructure\Sonata\Doctrine;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Infrastructure\Sonata\Doctrine\ModelManager;
use Prophecy\Argument;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class ModelManagerSpec extends ObjectBehavior
{
    public function let(DataPersisterInterface $dataPersister, ManagerRegistry $registry)
    {
        $this->beConstructedWith($dataPersister, $registry);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(ModelManager::class);
    }

    public function it_is_able_to_instantiate_a_entity_without_call_the_constructor()
    {
        $this->getModelInstance(ConcreteEntity::class)
            ->getName()
            ->shouldReturn('original');
    }

    public function it_throws_an_exception_when_try_instantiate_an_abstract_class()
    {
        $this->shouldThrow(\RuntimeException::class)
            ->during('getModelInstance', [AbstractEntity::class]);
    }

    public function it_is_able_to_create_a_new_entity(DataPersisterInterface $dataPersister)
    {
        $entity = Argument::any();

        $this->create($entity);
        $dataPersister->persist($entity)->shouldHaveBeenCalled();
    }

    public function it_is_able_to_update_an_entity(DataPersisterInterface $dataPersister)
    {
        $entity = Argument::any();

        $this->update($entity);
        $dataPersister->persist($entity)->shouldHaveBeenCalled();
    }


    public function it_is_able_to_remove_an_entity(DataPersisterInterface $dataPersister){
        $entity = Argument::any();

        $this->delete($entity);
        $dataPersister->remove($entity)->shouldHaveBeenCalled();
    }
}

abstract class AbstractEntity
{
    abstract public function getName(): string;
}

class ConcreteEntity extends AbstractEntity
{
    private string  $name = 'original';

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
