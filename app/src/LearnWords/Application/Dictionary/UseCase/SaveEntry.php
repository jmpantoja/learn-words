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

namespace LearnWords\Application\Dictionary\UseCase;

use LearnWords\Domain\Dictionary\Entry;

final class SaveEntry
{

    private Entry $entry;

    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
    }

    /**
     * @return Entry
     */
    public function getEntry(): Entry
    {
        return $this->entry;
    }
}
