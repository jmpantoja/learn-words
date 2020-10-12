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
use LearnWords\Domain\User\LeitnerStatus;
use LearnWords\Domain\User\Stat;
use LearnWords\Domain\User\StatList;
use LearnWords\Domain\User\StatRepository;
use LearnWords\Domain\User\User;

final class StatDoctrineRepository extends ServiceEntityRepository implements StatRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stat::class);
    }

    public function persist(Stat $stat): void
    {
        $this->getEntityManager()->persist($stat);
    }

    public function createIfNotExists(User $user, LeitnerStatus $status): self
    {
        $stat = $this->findOneBy([
            'user' => $user,
            'date' => CarbonImmutable::today()
        ]);

        if ($stat instanceof Stat) {
            $stat->update($status);
            $this->persist($stat);
            return $this;
        }

        $stat = new Stat($user, $status);
        $this->persist($stat);
        return $this;
    }

    public function byUser(User $user): StatList
    {
        $query = $this->createQueryBuilder('S')
            ->where('S.user = :user')
            ->orderBy('S.date', 'DESC')
            ->setMaxResults(7)
            ->setParameter('user', $user)
            ->getQuery();

        $stats = $query->execute();

        return StatList::collect($stats);
    }


}
