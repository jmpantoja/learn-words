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
use LearnWords\Domain\Dictionary\EntryPersistence\EntryHasBeenDeleted;
use LearnWords\Domain\Dictionary\EntryPersistence\TagHasBeenDeleted;
use LearnWords\Domain\Dictionary\EntryRepository;
use LearnWords\Domain\Dictionary\Word;

final class EntryDoctrineRepository extends ServiceEntityRepository implements EntryRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entry::class);
    }


    public function persist(Entry $entry): void
    {
        $this->getEntityManager()->persist($entry);
    }

    public function delete(Entry $entry): void
    {
        $entry->notify(new EntryHasBeenDeleted($entry));

        $this->getEntityManager()->remove($entry);
    }

    public function findByWord(Word $word): ?Entry
    {
        return $this->findOneBy([
                'word.word' => $word->getWord(),
                'word.lang' => $word->getLang()
            ]);
    }
}
