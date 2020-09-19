<?php

namespace spec\LearnWords\Infrastructure\Domain\Dictionary\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Persisters\Entity\EntityPersister;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ManagerRegistry;
use LearnWords\Domain\Dictionary\Tag;
use LearnWords\Domain\Dictionary\TagPersistence\TagHasBeenDeleted;
use LearnWords\Infrastructure\Domain\Dictionary\Repository\TagDoctrineRepository;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use PlanB\Edge\Domain\Event\DomainEventsCollector;
use Prophecy\Argument;

class TagDoctrineRepositorySpec extends ObjectBehavior
{
    public function let(ManagerRegistry $registry,
                        EntityManagerInterface $entityManager,
                        ClassMetadata $metadata,
                        UnitOfWork $unitOfWork,
                        EntityPersister $persister,
                        DomainEventsCollector $eventsCollector
    )
    {
        $eventsCollector->handle(Argument::any())->willReturn($eventsCollector);

        $registry->getManagerForClass(Tag::class)->willReturn($entityManager);
        $entityManager->getClassMetadata(Tag::class)->willReturn($metadata);
        $entityManager->getUnitOfWork()->willReturn($unitOfWork);
        $unitOfWork->getEntityPersister(Argument::any())->willReturn($persister);

        $this->beConstructedWith($registry);

        DomainEventDispatcher::getInstance()
            ->setDomainEventsCollector($eventsCollector->getWrappedObject());

    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TagDoctrineRepository::class);
    }

    public function it_is_able_to_persist_a_word(Tag $tag, EntityManager $entityManager)
    {
        $this->persist($tag);

        $entityManager->persist($tag)
            ->shouldBeCalledOnce();
    }

    public function it_is_able_to_delete_a_word(Tag $tag,
                                                EntityManager $entityManager,
                                                DomainEventsCollector $eventsCollector)
    {
        $this->delete($tag);
        $entityManager->remove($tag)
            ->shouldBeCalledOnce();

        $eventsCollector->handle(Argument::type(TagHasBeenDeleted::class))
            ->shouldHaveBeenCalledOnce();

    }

    public function it_is_able_to_find_a_tag_by_name(EntityPersister $persister)
    {
        $this->findByName('name');
        $persister->load([
            'tag' => 'name'
        ], Argument::cetera())->shouldBeCalledOnce();
    }

    public function it_creates_a_tag_only_if_not_exists(EntityPersister $persister, EntityManagerInterface $entityManager)
    {
        $persister->load(Argument::cetera())->willReturn(null);
        $this->createIfNotExits('name');

        $entityManager
            ->persist(Argument::type(Tag::class))
            ->shouldBeCalledOnce();
    }

    public function it_creates_a_tag_list_by_strings(EntityPersister $persister, EntityManagerInterface $entityManager)
    {
        $tagA = new Tag('tagA');

        $persister->load([
            'tag' => 'tagA'
        ], Argument::cetera())->willReturn($tagA);

        $persister->load([
            'tag' => 'tagB'
        ], Argument::cetera())->willReturn(null);

        $entityManager
            ->persist(Argument::type(Tag::class))
            ->shouldBeCalledOnce();

        $tagList = $this->createTagList('tagA', 'tagB');

        $tagList->count()->shouldReturn(2);

        $tagList->get(0)->getTag()->shouldReturn('tagA');
        $tagList->get(1)->getTag()->shouldReturn('tagB');
    }


    public function it_returns_a_tag_if_already_exists(EntityPersister $persister, EntityManagerInterface $entityManager)
    {
        $tag = new Tag('name');

        $persister->load(Argument::cetera())->willReturn($tag);
        $this->createIfNotExits('name')->shouldReturn($tag);

        $entityManager->persist(Argument::type(Tag::class))->shouldNotHaveBeenCalled();
    }


}
