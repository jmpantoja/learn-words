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

namespace LearnWords\Domain\Dictionary;


final class Question
{

    private QuestionId $id;
    private Entry $entry;
    private Wording $wording;
    private Example $example;
    private Relevance $relevance;

    public function __construct(Entry $entry, Wording $wording, Example $example, Relevance $relevance)
    {
        $this->id = new QuestionId();
        $this->entry = $entry;
        $this->wording = $wording;
        $this->example = $example;
        $this->relevance = $relevance;
    }

    public function update(Wording $wording, Example $example, Relevance $relevance): self
    {
        $this->wording = $wording;
        $this->example = $example;
        $this->relevance = $relevance;
        return $this;
    }

    public function getEntry(): Entry
    {
        return $this->entry;
    }

    public function getWording(): Wording
    {
        return $this->wording;
    }

    public function getExample(): Example
    {
        return $this->example;
    }

    public function getRelevance(): Relevance
    {
        return $this->relevance;
    }
}
