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

final class EntriesCSVReader extends CSVReader
{

    protected function rowToData(array $row): array
    {
        return [
            'word' => array_shift($row),
            'notes' => array_shift($row),
            'tags' => $row,
        ];
    }
}
