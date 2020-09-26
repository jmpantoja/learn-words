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

namespace LearnWords\Infrastructure\UI\GraphQl\Resolver;


use LearnWords\Domain\Dictionary\Tag;
use LearnWords\Domain\Dictionary\TagRepository;
use Overblog\GraphQLBundle\Definition\Resolver\AliasedInterface;
use Overblog\GraphQLBundle\Definition\Resolver\ResolverInterface;

final class TagResolver implements ResolverInterface, AliasedInterface
{

    private TagRepository $repository;

    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllTags()
    {
        $tagList = $this->repository->getAll();

        return array_map(function (Tag $tag) {
            return ['tag' => $tag->getTag()];
        }, $tagList->toArray());

        return [
            ['tag' => 'aaa'],
            ['tag' => 'bbb'],
        ];
    }

    public static function getAliases(): array
    {
        return [
            'getAllTags' => 'all_tags'
        ];
    }
}
