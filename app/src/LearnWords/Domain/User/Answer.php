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

namespace LearnWords\Domain\User;


use Carbon\CarbonImmutable;
use LearnWords\Domain\Dictionary\Question;

final class Answer
{
    private AnswerId $id;

    private User $user;
    private Question $question;

    private Leitner $leitner;
    private int $totalFailures;
    private int $totalSuccess;
    private ?CarbonImmutable $lastDate;
    private CarbonImmutable $nextDate;
    private int $next;

    public function __construct(User $user, Question $question)
    {
        $this->id = new AnswerId();
        $this->user = $user;
        $this->question = $question;
        $this->initial();

        $this->leitner = Leitner::INITIAL();
    }

    private function initial()
    {
        $this->leitner = Leitner::INITIAL();
        $this->totalFailures = 0;
        $this->totalSuccess = 0;

        $this->lastDate = null;
        $this->setNextDate(CarbonImmutable::today());
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    /**
     * @return Leitner
     */
    public function getLeitner(): Leitner
    {
        return $this->leitner;
    }

    public function getTotalFailures(): int
    {
        return $this->totalFailures;
    }

    public function getTotalSuccess(): int
    {
        return $this->totalSuccess;
    }

    public function getLastDate(): ?CarbonImmutable
    {
        return $this->lastDate;
    }

    public function getNextDate(): CarbonImmutable
    {
        return $this->nextDate;
    }

    public function getNext()
    {
        return $this->next;
    }

    private function setNextDate(CarbonImmutable $newDate): self
    {
        $this->nextDate = $newDate;
        $this->next = $this->nextDate->format('Ymd') * 1;
        return $this;
    }


    public function resolve(AnswerStatus $status): self
    {
        $this->lastDate = CarbonImmutable::today();
        if ($status->isWrong()) {
            return $this->beWrong();
        }
        return $this->beRight();

    }

    private function beWrong(): self
    {
        $this->leitner = Leitner::INITIAL();
        $this->setNextDate(CarbonImmutable::today());
        $this->totalFailures++;

        return $this;
    }

    private function beRight(): self
    {
        $this->leitner = $this->leitner->next();
        $newDate = $this->leitner->getDateWithIncrement();

        $this->setNextDate($newDate);
        $this->totalSuccess++;

        return $this;
    }
}
