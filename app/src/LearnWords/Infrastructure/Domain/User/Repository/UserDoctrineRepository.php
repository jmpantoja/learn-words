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
use LearnWords\Domain\User\User;
use LearnWords\Domain\User\UserRepository;

final class UserDoctrineRepository extends ServiceEntityRepository implements UserRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }


    public function persist(User $user): void
    {
        $this->getEntityManager()->persist($user);
    }

    public function delete(User $user): void
    {
        $this->getEntityManager()->remove($user);
    }
}
