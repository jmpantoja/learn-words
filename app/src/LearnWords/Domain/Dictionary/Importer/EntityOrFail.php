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
use LearnWords\Domain\Dictionary\Word;

final class EntityOrFail
{
    private object $entity;
    private string $label;
    private string $failure;
    private bool $success;

    static public function success(object $entity, Word $word): self
    {
        $entityOrFail = new static();
        $entityOrFail->entity = $entity;
        $entityOrFail->label = $word->getWord();
        $entityOrFail->success = true;

        return $entityOrFail;
    }

    static public function failure(string $word, string $failure): self
    {
        $entityOrFail = new static();
        $entityOrFail->label = $word;
        $entityOrFail->failure = $failure;
        $entityOrFail->success = false;

        return $entityOrFail;
    }

    /**
     * @return Entry
     */
    public function getEntity(): object
    {
        return $this->entity;
    }

    /**
     * @return string
     */
    public function getFailureReason(): string
    {
        return $this->failure;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }


}
