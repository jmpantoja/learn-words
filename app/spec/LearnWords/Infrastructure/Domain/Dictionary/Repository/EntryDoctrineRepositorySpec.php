<?php

namespace spec\LearnWords\Infrastructure\Domain\Dictionary\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Persisters\Entity\EntityPersister;
use Doctrine\ORM\UnitOfWork;
use Doctrine\Persistence\ManagerRegistry;
use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\EntryPersistence\EntryHasBeenDeleted;
use LearnWords\Domain\Dictionary\Lang;
use LearnWords\Domain\Dictionary\Word;
use LearnWords\Infrastructure\Domain\Dictionary\Repository\EntryDoctrineRepository;
use PhpSpec\ObjectBehavior;
use PlanB\Edge\Domain\Event\DomainEventDispatcher;
use PlanB\Edge\Domain\Event\DomainEventsCollector;
use Prophecy\Argument;

class EntryDoctrineRepositorySpec extends ObjectBehavior
{
    public function let(ManagerRegistry $registry,
                        EntityManager $entityManager,
                        ClassMetadata $metadata,
                        UnitOfWork $unitOfWork,
                        EntityPersister $persister,
                        DomainEventsCollector $eventsCollector
    )
    {
        $eventsCollector->handle(Argument::any())->willReturn($eventsCollector);

        $registry->getManagerForClass(Entry::class)->willReturn($entityManager);
        $entityManager->getClassMetadata(Entry::class)->willReturn($metadata);

        $entityManager->getUnitOfWork()->willReturn($unitOfWork);
        $unitOfWork->getEntityPersister(Argument::any())->willReturn($persister);

        $this->beConstructedWith($registry);

        DomainEventDispatcher::getInstance()
            ->setDomainEventsCollector($eventsCollector->getWrappedObject());

    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(EntryDoctrineRepository::class);
    }

    public function it_is_able_to_persist_a_word(Entry $entry, EntityManager $entityManager)
    {
        $this->persist($entry);

        $entityManager->persist($entry)
            ->shouldBeCalledOnce();
    }

    public function it_is_able_to_delete_a_word(Entry $entry,
                                                EntityManager $entityManager,
                                                DomainEventsCollector $eventsCollector)
    {
        $this->delete($entry);
        $entityManager->remove($entry)
            ->shouldBeCalledOnce();

        $eventsCollector->handle(Argument::type(EntryHasBeenDeleted::class))
            ->shouldHaveBeenCalledOnce();
    }

    public function it_is_able_to_find_an_entry_by_word(EntityPersister $persister)
    {
        $word = Word::English('hello');
        $this->findByWord($word);
        $persister->load([
            'word.word' => 'hello',
            'word.lang' => 'ENGLISH'
        ], Argument::cetera())->shouldBeCalledOnce();
    }


}
