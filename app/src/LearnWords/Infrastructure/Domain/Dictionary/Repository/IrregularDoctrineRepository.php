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
use Doctrine\Persistence\ManagerRegistry;
use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\EntryPersistence\TagHasBeenDeleted;
use LearnWords\Domain\Dictionary\Irregular;
use LearnWords\Domain\Dictionary\IrregularRepository;

final class IrregularDoctrineRepository extends ServiceEntityRepository implements IrregularRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Irregular::class);
    }

    public function findByEntry(Entry $entry): ?Irregular
    {
        return $this->findOneBy([
            'entry' => $entry
        ]);
    }

    public function persist(Irregular $irregular): void
    {
        $this->getEntityManager()->persist($irregular);
    }

    public function delete(Irregular $irregular): void
    {
        $this->getEntityManager()->remove($irregular);
    }

}
