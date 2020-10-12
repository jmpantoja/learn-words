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


use Generator;
use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\Importer\Reader\ReaderInterface;
use LearnWords\Domain\Dictionary\Irregular;
use LearnWords\Domain\Dictionary\IrregularRepository;
use LearnWords\Domain\Dictionary\Lang;

final class ImportVerbsResolver implements ImportResolverInterface
{

    /**
     * @var ImportEntriesResolver
     */
    private ImportEntriesResolver $entriesResolver;
    /**
     * @var IrregularRepository
     */
    private IrregularRepository $repository;

    public function __construct(IrregularRepository $repository, ImportEntriesResolver $entriesResolver)
    {
        $this->repository = $repository;
        $this->entriesResolver = $entriesResolver;
    }

    /**
     * @param ReaderInterface $reader
     * @param Lang $lang
     * @return Generator
     */
    public function resolve(ReaderInterface $reader, Lang $lang): Generator
    {
        foreach ($reader as $row) {
            yield $this->buildQuestion($row, $lang);
        }
    }

    public function buildQuestion(array $row, Lang $lang): EntityOrFail
    {
        $entityOrFail = $this->entriesResolver->buildEntry([
            'word' => $row['infinitive'],
            'tags' => ['irregular verbs']
        ], $lang);

        if (!$entityOrFail->isSuccess()) {
            return $entityOrFail;
        }

        $entry = $entityOrFail->getEntity();

        $question = $this->createIfNotExists(...[
            $entry,
            $row['past_simple'],
            $row['past_participle']
        ]);

        return EntityOrFail::success($question, $entry->getWord());
    }

    /**
     * @param EntityOrFail $entityOrFail
     * @param array $row
     * @return Irregular
     */
    private function createIfNotExists(Entry $entry, string $simple, string $participle): Irregular
    {
        $irregular = $this->repository->findByEntry($entry);
        return $irregular ?? new Irregular($entry, $simple, $participle);
    }
}
