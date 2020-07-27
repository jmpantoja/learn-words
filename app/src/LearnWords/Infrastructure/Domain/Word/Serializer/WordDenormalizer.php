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
use LearnWords\Domain\Word\Question;
use LearnWords\Domain\Word\TagList;
use LearnWords\Domain\Word\Word;
use PlanB\Edge\Domain\Collection\SnapshotList;
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
        $word = $this->buildWord($data, $word);

        $questions = SnapshotList::collect($data['questions'] ?? []);

        $this->updateQuestions($word, $questions);

        return $word;
    }

    protected function buildWord(array $data, ?Word $word): Word
    {
        $value = $data['word'];
        $lang = $this->partial($data['lang'], Lang::class);
        $tagList = $this->partial($data['tags'], TagList::class);

        if (is_null($word)) {
            return new Word($value, $lang, $tagList);
        }

        return $word->update($value, $lang, $tagList);
    }

    /**
     * @param Word $word
     * @param SnapshotList|null $questions
     */
    protected function updateQuestions(Word $word, ?SnapshotList $questions): void
    {
        if (!($questions instanceof SnapshotList)) {
            return;
        }

        $questions->eachUpdate(function (array $question, int $key) use ($word) {
            return $word->updateQuestion($key, $question['wording']);
        });

        $questions->eachDelete(function (Question $question) use ($word) {
            return $word->removeQuestion($question);
        });

        $questions->eachInsert(function (array $question) use ($word) {
            return $word->addQuestion($question['wording']);
        });
    }
}
