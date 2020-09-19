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

namespace LearnWords\Infrastructure\Domain\Dictionary\Transformer;


use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\Question;
use LearnWords\Infrastructure\Domain\Dictionary\Dto\EntryDto;
use PlanB\Edge\Domain\Collection\SnapshotList;
use PlanB\Edge\Domain\Dto\Dto;
use PlanB\Edge\Domain\Transformer\Builder;
use PlanB\Edge\Domain\Transformer\Transformer;

final class EntryNormalizer extends Transformer
{
    /**
     * @inheritDoc
     */
    public function supportsDenormalization($data, $type, $format = null)
    {
        return $type === Entry::class;
    }

    /**
     * @param Dto $data
     * @return Entry
     */
    public function create(Dto $data): Entry
    {
        $entry = new Entry($data->word, $data->tags);
        return $this->applyChanges($data, $entry);
    }

    /**
     * @param Dto $data
     * @param Entry $entry
     * @return Entry
     */
    public function update(Dto $data, $entry): Entry
    {
        $entry->update($data->word, $data->tags);
        return $this->applyChanges($data, $entry);
    }

    private function applyChanges(EntryDto $data, Entry $entry): Entry
    {
        $this->applyQuestions($data->questions, $entry);
        return $entry;
    }

    private function applyQuestions(SnapshotList $questions, Entry $entry)
    {
        $questions->eachDelete(fn(Question $question) => $entry->removeQuestion($question));
        $questions->eachUpdate(fn($key, array $question) => $entry->updateQuestion($key, $question));
        $questions->eachInsert(fn(array $question) => $entry->addQuestion($question));
    }
}
