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

use LearnWords\Term\Domain\Model\TermId;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Domain\Entity\EntityInterface;

final class Term implements EntityInterface
{
    private ?EntityId $id = null;
    private Word $word;

    public function __construct(Word $word)
    {
        $this->id = new TermId();
        $this->update($word);
    }

    public function update(Word $word): self
    {
        $this->setWord($word);
        return $this;
    }

    /**
     * @return TermId
     */
    public function id(): ?EntityId
    {
        return $this->id;
    }

    /**
     * @return Word
     */
    public function word(): Word
    {
        return $this->word;
    }

    public function setWord(Word $word): self
    {
        $this->word = $word;
        return $this;
    }
}
