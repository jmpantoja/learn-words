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
use LearnWords\Domain\Dictionary\Importer\Reader\ReaderInterface;
use LearnWords\Domain\Dictionary\Lang;

interface ImportResolverInterface
{
    public function resolve(ReaderInterface $reader, Lang $lang): Generator;
}
