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


use LearnWords\Domain\User\GivenText;

abstract class Question
{
    protected QuestionId $id;
    protected Entry $entry;
    protected int $random;

    public function __construct(Entry $entry)
    {
        $this->id = new QuestionId();
        $this->entry = $entry;
        $this->random = random_int(1, 100000);
    }

    /**
     * @return QuestionId
     */
    public function getId(): QuestionId
    {
        return $this->id;
    }

    public function getEntry(): Entry
    {
        return $this->entry;
    }

    public function getWord(): Word
    {
        return $this->getEntry()->getWord();
    }

    public function match(GivenText $response): bool
    {
        $word = $this->getWord();
        return $response->match((string)$word);
    }
}
