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

namespace LearnWords\Infrastructure\Domain\Word\Serializer;


use LearnWords\Domain\Word\Tag;
use LearnWords\Domain\Word\TagRepository;
use PlanB\Edge\Infrastructure\Symfony\Normalizer\Denormalizer;

final class TagDenormalizer extends Denormalizer
{
    /**
     * @var TagRepository
     */
    private TagRepository $repository;

    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return is_array($data) && $type === Tag::class;
    }

    protected function mapToObject($data, ?Tag $tag = null): object
    {
        $label = $data['tag'];

        if (is_null($tag)) {
            return $this->repository->findByLabel($label) ?? new Tag($label);
        }

        return $tag->update($label);
    }
}
