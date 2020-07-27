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


use LearnWords\Domain\Word\Question;
use LearnWords\Domain\Word\WordId;
use PlanB\Edge\Domain\Event\DomainEvent;

final class QuestionHasBeenAdded extends DomainEvent
{
    /**
     * @var WordId
     */
    private WordId $word;
    private string $wording;

    /**
     * QuestionHasBeenAdded constructor.
     */
    public function __construct(WordId $word, Question $question)
    {
        $this->word = $word;
        $this->wording = $question->getWording();
        parent::__construct();
    }

    /**
     * @return WordId
     */
    public function getWord(): WordId
    {
        return $this->word;
    }

    /**
     * @return string
     */
    public function getWording(): string
    {
        return $this->wording;
    }
}
