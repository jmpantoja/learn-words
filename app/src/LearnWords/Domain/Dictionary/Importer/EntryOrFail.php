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

final class EntryOrFail
{
    private Entry $entry;
    private string $failure;
    private bool $success;

    static public function success(Entry $entry): self
    {
        $entryOfFail = new static();
        $entryOfFail->entry = $entry;
        $entryOfFail->success = true;

        return $entryOfFail;
    }

    static public function failure(string $failure): self
    {
        $entryOfFail = new static();
        $entryOfFail->failure = $failure;
        $entryOfFail->success = false;

        return $entryOfFail;
    }

    /**
     * @return Entry
     */
    public function getEntry(): Entry
    {
        return $this->entry;
    }

    /**
     * @return string
     */
    public function getFailure(): string
    {
        return $this->failure;
    }

    public function getWord(): string
    {
        if ($this->isSuccess()) {
            return (string)$this->entry->getWord();
        }

        return $this->failure;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }


}
