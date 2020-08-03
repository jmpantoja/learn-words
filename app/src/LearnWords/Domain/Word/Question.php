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

namespace LearnWords\Domain\Word;


use PlanB\Edge\Domain\Entity\EntityInterface;

final class Question implements EntityInterface
{
    private QuestionId $id;
    private Word $word;

    private string $wording;

    public function __construct(Word $word, string $wording)
    {
        $this->id = new QuestionId();
        $this->word = $word;
        $this->wording = $wording;
    }

    public function update(string $wording): self
    {
        if ($this->wording == $wording) {
            return $this;
        }

        $this->wording = $wording;
        return $this;
    }

    public function getId(): QuestionId
    {
        return $this->id;
    }

    public function getWord(): Word
    {
        return $this->word;
    }

    public function getWording(): string
    {
        return $this->wording;
    }

}
