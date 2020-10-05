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


use Carbon\CarbonImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use LearnWords\Domain\Dictionary\Vocabulary;
use LearnWords\Domain\Dictionary\VocabularyCriteria;
use LearnWords\Domain\Dictionary\VocabularyList;
use LearnWords\Domain\Dictionary\VocabularyRepository;
use LearnWords\Domain\User\Answer;
use LearnWords\Domain\User\Leitner;
use LearnWords\Domain\User\User;

final class VocabularyDoctrineRepository extends ServiceEntityRepository implements VocabularyRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vocabulary::class);
    }

    public function getDailyReview(User $user): VocabularyList
    {
        $questions = $this->configureDailyReviewQuery($user)
            ->getQuery()
            ->execute();

        return VocabularyList::collect($questions);
    }

    public function getDailyReviewCount(User $user): int
    {
        return $this->configureDailyReviewQuery($user)
                ->select('count(V.id)')
                ->getQuery()
                ->getSingleScalarResult() * 1;

    }

    /**
     * @param User $user
     * @return QueryBuilder
     */
    private function configureDailyReviewQuery(User $user): QueryBuilder
    {
        $next = CarbonImmutable::today()->format('Ymd');
        return $this->createQueryBuilder('V')
            ->innerJoin(Answer::class, 'A', Join::WITH, 'A.question = V.id and A.user = :user')
            ->where('A.next <= :next')
            ->setParameters([
                'user' => $user,
                'next' => $next
            ]);
    }


    public function getVocabularyByCriteria(VocabularyCriteria $criteria): VocabularyList
    {
        $limit = $criteria->getLimit()->toInt();

        $query = $this->createQueryBuilder('V')
            ->distinct()
            ->select('V as vocabulary, A.leitner')
            ->innerJoin('V.entry', 'E')
            ->leftJoin(Answer::class, 'A', Join::WITH, 'A.question = V.id and A.user = :user')
            ->innerJoin('E.tags', 'T',)
            ->where('A.leitner = :initial OR A.leitner is null')
            ->andWhere('V.relevance <= :relevance')
            ->andWhere('T.tag IN (:tags)')
            ->orderBy('A.leitner', 'DESC')
            ->addOrderBy('V.random', 'ASC')
            ->setMaxResults($limit)
            ->setParameters([
                'user' => $criteria->getUser(),
                'relevance' => $criteria->getRelevance()->toInt(),
                'tags' => $criteria->getTags(),
                'initial' => Leitner::INITIAL()
            ])
            ->getQuery();


        $questions = array_map(function (array $item) {
            return $item['vocabulary'];
        }, $query->execute());

        return VocabularyList::collect($questions);
    }
}
