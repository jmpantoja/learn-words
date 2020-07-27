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
use LearnWords\Domain\Word\TagList;
use PlanB\Edge\Infrastructure\Symfony\Normalizer\Denormalizer;

final class TagListDenormalizer extends Denormalizer
{
    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return $type === TagList::class;
    }

    protected function mapToObject($data, ?TagList $tagList = null): TagList
    {
        if($data instanceof TagList){
            return $data;
        }

        $tags = [];
        foreach ($data as $tag) {
            $tags[] = $this->partial($tag, Tag::class);
        }
        return TagList::collect($tags);
    }
}
