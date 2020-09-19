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

namespace LearnWords\Domain\Dictionary\Importer;


use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\EntryRepository;
use LearnWords\Domain\Dictionary\Importer\Provider\QuestionProviderInterface;
use LearnWords\Domain\Dictionary\Importer\Reader\EntriesReaderInterface;
use LearnWords\Domain\Dictionary\Lang;
use LearnWords\Domain\Dictionary\TagRepository;
use LearnWords\Domain\Dictionary\Word;

final class ImportEntriesResolver implements ImportEntriesResolverInterface
{
    private TagRepository $tagRepository;

    private QuestionProviderInterface $questionProvider;
    /**
     * @var EntryRepository
     */
    private EntryRepository $entryRepository;

    public function __construct(QuestionProviderInterface $questionProvider,
                                EntryRepository $entryRepository,
                                TagRepository $tagRepository)
    {
        $this->questionProvider = $questionProvider;
        $this->entryRepository = $entryRepository;
        $this->tagRepository = $tagRepository;
    }

    /**
     * @param EntriesReaderInterface $reader
     * @param Lang $lang
     * @return ImportEntryResponse[]
     */
    public function resolve(EntriesReaderInterface $reader, Lang $lang): array
    {
        $entries = [];
        foreach ($reader as $row) {
            $entries[] = $this->buildEntry($row, $lang);
        }

        return $entries;
    }

    protected function buildEntry(array $row, Lang $lang): Entry
    {
        $word = new Word($row['word'], $lang);
        return $this->createEntryIfNotExists($word, ...$row['tags']);
    }

    /**
     * @param Word $word
     * @param string ...$tags
     * @return Entry
     */
    protected function createEntryIfNotExists(Word $word, string ...$tags): Entry
    {
        $tags = $this->tagRepository->createTagList(...$tags);

        $entry = $this->entryRepository->findByWord($word);
        if ($entry instanceof Entry) {
            return $entry->setTags($tags);
        }

        $questions = $this->questionProvider->byWord($word);

        return new Entry($word, $tags, $questions);
    }

}
