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


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use LearnWords\Domain\DailyWork\QuestionCriteria;
use LearnWords\Domain\Dictionary\Question;
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

    public function currentStat()
    {
        $query = $this->createQueryBuilder('A')
            ->select('count(A) as total, A.leitner, IDENTITY(A.user) as user')
            ->groupBy('A.user')
            ->addGroupBy('A.leitner')
            ->getQuery();

        return $query->execute();
    }

}
