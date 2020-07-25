<?php

namespace spec\LearnWords\Infrastructure\Domain\Word\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use LearnWords\Domain\Word\Tag;
use LearnWords\Domain\Word\TagPersistence\TagHasBeenDeleted;
use LearnWords\Domain\Word\Word;
use LearnWords\Domain\Word\WordPersistence\WordHasBeenCreated;
use LearnWords\Domain\Word\WordPersistence\WordHasBeenDeleted;
use LearnWords\Infrastructure\Domain\Word\Repository\TagDoctrineRepository;
use LearnWords\Infrastructure\Domain\Word\Repository\WordDoctrineRepository;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use PlanB\Edge\Domain\Event\DomainEventsCollector;
use Prophecy\Argument;

class TagDoctrineRepositorySpec extends ObjectBehavior
{
    public function let(ManagerRegistry $registry,
                        EntityManager $entityManager,
                        ClassMetadata $metadata,
                        DomainEventsCollector $eventsCollector
    )
    {
        $eventsCollector->handle(Argument::any(), Argument::any())->willReturn($eventsCollector);

        $registry->getManagerForClass(Tag::class)->willReturn($entityManager);
        $entityManager->getClassMetadata(Tag::class)->willReturn($metadata);

        $this->beConstructedWith($registry);

        DomainEventDispatcher::getInstance()
            ->setDomainEventsCollector($eventsCollector->getWrappedObject());

    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TagDoctrineRepository::class);
    }

    public function it_is_able_to_persist_a_word(Tag $tag,
                                                 EntityManager $entityManager)
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

        $eventsCollector->handle(Argument::type(TagHasBeenDeleted::class), TagHasBeenDeleted::class)
            ->shouldHaveBeenCalledOnce();
    }
}
