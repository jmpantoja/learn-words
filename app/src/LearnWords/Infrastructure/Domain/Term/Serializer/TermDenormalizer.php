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

namespace LearnWords\Infrastructure\Domain\Term\Serializer;


use LearnWords\Domain\Tag\TagList;
use LearnWords\Domain\Term\Term;
use LearnWords\Domain\Term\TermId;
use LearnWords\Domain\Term\Word;
use PlanB\Edge\Infrastructure\Symfony\Normalizer\Denormalizer;

final class TermDenormalizer extends Denormalizer
{
    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return $type === Term::class;
    }

    protected function mapToObject($data, ?Term $term = null): object
    {
        $word = $this->partial($data['word'], Word::class);
        $tagList = TagList::collect($data['tags']);

        if (is_null($term)) {
            return new Term($word, $tagList);
        }

        return $term->update($word, $tagList);
    }
}
