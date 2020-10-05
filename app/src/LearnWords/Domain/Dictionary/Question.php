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


abstract class Question
{
    protected QuestionId $id;
    protected Entry $entry;
    protected Wording $wording;
    protected int $random;

    public function __construct(Entry $entry, Wording $wording)
    {
        $this->id = new QuestionId();
        $this->entry = $entry;
        $this->wording = $wording;
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

    public function getWording(): Wording
    {
        return $this->wording;
    }
}
