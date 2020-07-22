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


use LearnWords\Domain\Word\Lang;
use LearnWords\Domain\Word\TagList;
use LearnWords\Domain\Word\Word;
use PlanB\Edge\Infrastructure\Symfony\Normalizer\Denormalizer;

final class WordDenormalizer extends Denormalizer
{
    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = [])
    {
        return $type === Word::class;
    }

    protected function mapToObject($data, ?Word $word = null): object
    {
        $value = $data['word'];
        $lang = $this->partial($data['lang'], Lang::class);

        $tagList = TagList::collect($data['tags']);

        if (is_null($word)) {
            return new Word($value, $lang, $tagList);
        }

        return $word->update($value, $lang, $tagList);
    }
}
