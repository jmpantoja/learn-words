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

namespace LearnWords\Infrastructure\Domain\Word\Repository;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use LearnWords\Domain\Word\Word;
use LearnWords\Domain\Word\WordPersistence\WordHasBeenDeleted;
use LearnWords\Domain\Word\WordRepository;

final class WordDoctrineRepository extends ServiceEntityRepository implements WordRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Word::class);
    }


    public function persist(Word $word): void
    {
        $this->getEntityManager()->persist($word);
    }

    public function delete(Word $word): void
    {
        $word->notify(new WordHasBeenDeleted($word));

        $this->getEntityManager()->remove($word);
    }

}
