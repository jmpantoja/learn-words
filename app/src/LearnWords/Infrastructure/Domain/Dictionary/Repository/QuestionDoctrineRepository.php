<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace LearnWords\Infrastructure\Domain\Dictionary\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;
use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\EntryPersistence\TagHasBeenDeleted;
use LearnWords\Domain\Dictionary\Question;
use LearnWords\Domain\Dictionary\QuestionRepository;

final class QuestionDoctrineRepository extends ServiceEntityRepository implements QuestionRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entry::class);
    }

    public function findByTag(string ...$tags): array
    {
        $entries = $this->getEntityManager()->createQueryBuilder()
            ->select('E')
            ->from(Entry::class, 'E')
            ->innerJoin('E.tags', 'T', Join::WITH, 'T.tag IN (:tags) ')
            ->setParameter('tags', $tags)
            ->getQuery()
            ->execute();

        return $this->getEntityManager()->createQueryBuilder()
            ->select('Q')
            ->from(Question::class, 'Q')
            ->where('Q.entry IN (:entries)')
            ->andWhere('Q.relevance <= :relevance')
            ->setParameter('entries', $entries)
            ->setParameter('relevance', 1)
            ->getQuery()
            ->execute();
    }
}
