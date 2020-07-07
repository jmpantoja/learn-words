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

namespace LearnWords\Term\Infrastructure\Doctrine\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use LearnWords\Term\Domain\Model\Term;
use LearnWords\Term\Domain\Repository\TermRepositoryInterface;

final class TermRepository extends ServiceEntityRepository implements TermRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Term::class);
    }

    public function persist(Term $term)
    {
        return $this->getEntityManager()->persist($term);
    }

    public function delete(Term $term)
    {
        return $this->getEntityManager()->remove($term);
    }
}
