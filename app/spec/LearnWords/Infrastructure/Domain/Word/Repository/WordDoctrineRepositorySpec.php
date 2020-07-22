<?php

namespace spec\LearnWords\Infrastructure\Domain\Word\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use LearnWords\Domain\Word\Word;
use LearnWords\Domain\Word\WordPersistence\WordHasBeenCreated;
use LearnWords\Domain\Word\WordPersistence\WordHasBeenDeleted;
use LearnWords\Infrastructure\Domain\Word\Repository\WordDoctrineRepository;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use PlanB\Edge\Domain\Event\DomainEventsCollector;
use Prophecy\Argument;

class WordDoctrineRepositorySpec extends ObjectBehavior
{
    public function let(ManagerRegistry $registry,
                        EntityManager $entityManager,
                        ClassMetadata $metadata,
                        DomainEventsCollector $eventsCollector
    )
    {
        $eventsCollector->handle(Argument::any(), Argument::any())->willReturn($eventsCollector);

        $registry->getManagerForClass(Word::class)->willReturn($entityManager);
        $entityManager->getClassMetadata(Word::class)->willReturn($metadata);

        $this->beConstructedWith($registry);

        DomainEventDispatcher::getInstance()
            ->setDomainEventsCollector($eventsCollector->getWrappedObject());

    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(WordDoctrineRepository::class);
    }

    public function it_is_able_to_persist_a_word(Word $word, EntityManager $entityManager)
    {
        $this->persist($word);

        $entityManager->persist($word)
            ->shouldBeCalledOnce();
    }

    public function it_is_able_to_delete_a_word(Word $word,
                                                EntityManager $entityManager,
                                                DomainEventsCollector $eventsCollector)
    {
        $this->delete($word);
        $entityManager->remove($word)
            ->shouldBeCalledOnce();

        $eventsCollector->handle(Argument::type(WordHasBeenDeleted::class), WordHasBeenDeleted::class)
            ->shouldHaveBeenCalledOnce();
    }
}
