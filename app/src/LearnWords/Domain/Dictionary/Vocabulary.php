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


class Vocabulary extends Question
{
    private ?Example $example;
    private Relevance $relevance;

    public function __construct(Entry $entry, Wording $wording, Relevance $relevance, ?Example $example)
    {
        $this->example = $example;
        $this->relevance = $relevance;
        parent::__construct($entry, $wording);
    }

    public function update(Wording $wording, Relevance $relevance, ?Example $example): self
    {
        $this->wording = $wording;
        $this->example = $example;
        $this->relevance = $relevance;

        return $this;
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
