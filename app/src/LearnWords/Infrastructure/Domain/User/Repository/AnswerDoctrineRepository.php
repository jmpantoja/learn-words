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

namespace LearnWords\Infrastructure\Domain\User\Repository;


use Carbon\CarbonImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use LearnWords\Domain\DailyWork\QuestionCriteria;
use LearnWords\Domain\Dictionary\Question;
use LearnWords\Domain\Dictionary\VocabularyList;
use LearnWords\Domain\User\Answer;
use LearnWords\Domain\User\AnswerRepository;
use LearnWords\Domain\User\User;

final class AnswerDoctrineRepository extends ServiceEntityRepository implements AnswerRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Answer::class);
    }


    public function persist(Answer $answer): void
    {
        $this->getEntityManager()->persist($answer);
    }

    public function createIfNotExists(User $user, Question $question): Answer
    {
        $answer = $this->findOneBy([
            'user' => $user,
            'question' => $question
        ]);

        if ($answer instanceof Answer) {
            return $answer;
        }

        return new Answer($user, $question);
    }
//
//    public function getQuestionsByUserAndCriteria(User $user, QuestionCriteria $criteria): QuestionList
//    {
//        $limit = $criteria->getLimit();
//        $today = CarbonImmutable::today()->format('Ymd');
//
//        $query = $this->createQueryBuilder('A')
//            ->innerJoin('A.question', 'Q')
//            ->innerJoin('Q.entry', 'E')
//            ->innerJoin('E.tags', 'T')
//            ->where('A.user = :user')
//            ->andWhere('Q.relevance <= :relevance')
//            ->andWhere('T.tag IN (:tags)')
//            ->andWhere('A.next <= :next')
//            ->setMaxResults($limit)
//            ->setParameters([
//                'user' => $user,
//                'relevance' => $criteria->getRelevance()->toInt(),
//                'tags' => $criteria->getTags(),
//                'next' => $today
//            ])
//            ->getQuery();
//
//        $answers = $query->execute();
//
//        $questions = array_map(function (Answer $answer) {
//            return $answer->getQuestion();
//        }, $answers);
//
//        return QuestionList::collect($questions);
//    }


}
