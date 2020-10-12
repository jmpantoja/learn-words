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


use Exception;
use Generator;
use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\EntryRepository;
use LearnWords\Domain\Dictionary\Importer\Provider\Mp3UrlProviderInteface;
use LearnWords\Domain\Dictionary\Importer\Provider\QuestionProviderInterface;
use LearnWords\Domain\Dictionary\Importer\Reader\ReaderInterface;
use LearnWords\Domain\Dictionary\Lang;
use LearnWords\Domain\Dictionary\Relevance;
use LearnWords\Domain\Dictionary\TagRepository;
use LearnWords\Domain\Dictionary\Word;

final class ImportEntriesResolver implements ImportResolverInterface
{
    private TagRepository $tagRepository;

    private QuestionProviderInterface $questionProvider;
    /**
     * @var EntryRepository
     */
    private EntryRepository $entryRepository;
    /**
     * @var Mp3UrlProviderInteface
     */
    private Mp3UrlProviderInteface $mp3UrlProvider;

    public function __construct(QuestionProviderInterface $questionProvider,
                                Mp3UrlProviderInteface $mp3UrlProvider,
                                EntryRepository $entryRepository,
                                TagRepository $tagRepository)
    {
        $this->questionProvider = $questionProvider;
        $this->mp3UrlProvider = $mp3UrlProvider;
        $this->entryRepository = $entryRepository;
        $this->tagRepository = $tagRepository;

    }

    /**
     * @param ReaderInterface $reader
     * @param Lang $lang
     * @return Generator
     */
    public function resolve(ReaderInterface $reader, Lang $lang): Generator
    {
        foreach ($reader as $row) {
            yield $this->buildEntry($row, $lang);
        }
    }

    public function buildEntry(array $row, Lang $lang): EntityOrFail
    {
        try {
            $word = new Word($row['word'], $lang);
            $entry = $this->createEntryIfNotExists($word, ...$row['tags']);
            return EntityOrFail::success($entry, $entry->getWord());

        } catch (Exception $exception) {
            return EntityOrFail::failure($row['word'], $exception->getMessage());
        }
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
        if (empty($questions)) {
            throw new Exception('Palabra no encontrada');
        }

        $mp3Url = $this->mp3UrlProvider->byWord($word);

        return new Entry($word, $tags, $questions, $mp3Url);
    }
}
