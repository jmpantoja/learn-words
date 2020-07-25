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
use LearnWords\Domain\Word\Tag;
use LearnWords\Domain\Word\TagPersistence\TagHasBeenDeleted;
use LearnWords\Domain\Word\TagRepository;
use LearnWords\Domain\Word\Word;
use LearnWords\Domain\Word\WordPersistence\WordHasBeenDeleted;
use LearnWords\Domain\Word\WordRepository;

final class TagDoctrineRepository extends ServiceEntityRepository implements TagRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag::class);
    }


    public function persist(Tag $tag): void
    {
        $this->getEntityManager()->persist($tag);
    }

    public function delete(Tag $tag): void
    {
        $tag->notify(new TagHasBeenDeleted($tag));
        $this->getEntityManager()->remove($tag);
    }
}
