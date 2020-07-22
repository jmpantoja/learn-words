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

namespace LearnWords\Domain\Word\WordPersistence;


use LearnWords\Domain\Word\Word;
use PlanB\Edge\Domain\Event\DomainEvent;

final class WordHasBeenCreated extends DomainEvent
{

    private Word $word;

    public function __construct(Word $word)
    {
        $this->word = $word;
        parent::__construct();
    }

    /**
     * @return Word
     */
    public function getWord(): Word
    {
        return $this->word;
    }

}
