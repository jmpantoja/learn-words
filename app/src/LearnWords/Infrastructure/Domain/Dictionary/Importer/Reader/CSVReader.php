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
use Iterator;
use LearnWords\Domain\Dictionary\Importer\Reader\ReaderInterface;
use SplFileInfo;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

abstract class CSVReader implements ReaderInterface
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

    public function count(): int
    {
        return count($this->rows);
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->rows);
    }

    abstract protected function rowToData(array $row): array;


}
