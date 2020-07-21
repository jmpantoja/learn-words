<?php

namespace spec\LearnWords\Infrastructure\Domain\Term\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use LearnWords\Domain\Term\Term;
use LearnWords\Infrastructure\Domain\Term\Repository\TermDoctrineRepository;
use PhpSpec\ObjectBehavior;

class TermDoctrineRepositorySpec extends ObjectBehavior
{
    public function let(ManagerRegistry $registry,
                        EntityManager $entityManager,
                        ClassMetadata $metadata)
    {

        $registry->getManagerForClass(Term::class)->willReturn($entityManager);
        $entityManager->getClassMetadata(Term::class)->willReturn($metadata);

        $this->beConstructedWith($registry);

    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(TermDoctrineRepository::class);
    }

    public function it_is_able_to_persist_a_term(Term $term, EntityManager $entityManager)
    {
        $this->persist($term);

        $entityManager->persist($term)
            ->shouldBeCalledOnce();
    }

    public function it_is_able_to_delete_a_term(Term $term, EntityManager $entityManager)
    {
        $this->delete($term);

        $entityManager->remove($term)
            ->shouldBeCalledOnce();
    }
}
