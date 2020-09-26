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
use LearnWords\Domain\Dictionary\Tag;
use LearnWords\Domain\Dictionary\TagList;
use LearnWords\Domain\Dictionary\TagPersistence\TagHasBeenDeleted;
use LearnWords\Domain\Dictionary\TagRepository;

final class TagDoctrineRepository extends ServiceEntityRepository implements TagRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tag ::class);
    }

    public function getAll(): TagList
    {
        return TagList::collect($this->findAll());
    }


    public function findByName(string $name): ?Tag
    {
        $name = trim($name);
        return $this->findOneBy([
            'tag' => $name
        ]);
    }

    public function createTagList(string ...$tags): TagList
    {
        $input = [];
        foreach ($tags as $tag) {
            $input[] = $this->createIfNotExits($tag);
        }
        return TagList::collect($input);
    }


    public function createIfNotExits(string $name): Tag
    {
        $tag = $this->findByName($name);

        if ($tag instanceof Tag) {
            return $tag;
        }

        $tag = new Tag($name);
        $this->persist($tag);

        return $tag;
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
