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

namespace LearnWords\Term\Domain\Model;

use PlanB\Edge\Domain\Entity\EntityInterface;
use PlanB\Edge\Domain\Entity\Traits\NotifyEvents;

final class Term implements EntityInterface
{
    use NotifyEvents;

    private ?TermId $id = null;
    private Word $word;

    public function __construct(TermId $termId, Word $word)
    {
        $this->id = $termId;
        $this->update($word);

        $this->notify(new TermHasBeenCreated($this));
    }

    public function update(Word $word): self
    {
        $this->setWord($word);

        $this->notify(new TermHasBeenUpdated($this));
        return $this;
    }

    /**
     * @return TermId
     */
    public function getId(): ?TermId
    {
        return $this->id;
    }

    /**
     * @return Word
     */
    public function getWord(): Word
    {
        return $this->word;
    }

    /**
     * @param Word $word
     * @return Term
     */
    private function setWord(Word $word): self
    {
        $this->word = $word;
        return $this;
    }
}
