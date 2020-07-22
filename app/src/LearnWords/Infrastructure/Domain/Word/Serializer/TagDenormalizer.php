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
use PlanB\Edge\Infrastructure\Symfony\Normalizer\Denormalizer;

final class TagDenormalizer extends Denormalizer
{
    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return $type === Tag::class;
    }

    protected function mapToObject($data, ?Tag $tag = null): object
    {
        $label = $data['tag'];
        if (is_null($tag)) {
            return new Tag($label);
        }

        return $tag->update($label);
    }
}