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

namespace LearnWords\Infrastructure\Domain\Dictionary\Importer\Reader;


use ArrayIterator;
use LearnWords\Domain\Dictionary\Importer\Reader\EntriesReaderInterface;
use SplFileInfo;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

final class EntriesCSVReader implements EntriesReaderInterface
{
    private SplFileInfo $fileInfo;

    private array $rows;

    public function __construct(SplFileInfo $fileInfo)
    {
        $header = str_repeat(',', 50);

        $this->fileInfo = $fileInfo;
        $encoder = new CsvEncoder([
            CsvEncoder::NO_HEADERS_KEY => true,
            CsvEncoder::AS_COLLECTION_KEY => true
        ]);

        $content = file_get_contents($fileInfo->getRealPath());
        $content = sprintf("%s\n$content", $header);

        $rows = $encoder->decode($content, 'csv');
        unset($rows[0]);
        $rows = array_values($rows);

        $this->rows = array_map(function (array $row) {
            return $this->rowToData($row);
        }, $rows);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->rows);
    }

    private function rowToData(array $row): array
    {
        return [
            'word' => array_shift($row),
            'notes' => array_shift($row),
            'tags' => $row,
        ];
    }
}
