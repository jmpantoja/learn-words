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
    private float $average;
    private ?CarbonImmutable $lastDate;
    private CarbonImmutable $nextDate;
    private int $next;

    public function __construct(User $user, Question $question)
    {
        $this->id = new AnswerId();
        $this->user = $user;
        $this->question = $question;
        $this->initial();
    }

    private function initial()
    {
        $this->leitner = Leitner::TODAY();
        $this->totalFailures = 0;
        $this->totalSuccess = 0;
        $this->average = 0;

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

    public function getTotal(): int
    {
        return $this->totalSuccess + $this->totalFailures;
    }

    public function getAverage(): float
    {
        return $this->average;
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


    public function dryRun(GivenText $response): self
    {
        if ($this->question->match($response)) {
            $this->totalSuccess++;
            $this->lastDate = CarbonImmutable::today();
            $this->calculeAverage();
            return $this;
        }

        $this->totalFailures++;
        $this->calculeAverage();
        return $this;
    }

    public function resolve(GivenText $response): self
    {
        $this->lastDate = CarbonImmutable::today();

        if ($response->isEmpty()) {
            return $this->beSeen();
        }

        if ($this->question->match($response)) {
            return $this->beRight();
        }
        return $this->beWrong();
    }

    private function beSeen(): self
    {
        if (!$this->leitner->is(Leitner::TODAY())) {
            return $this;
        }

        $this->initial();

        return $this;
    }

    private function beWrong(): self
    {
        $this->leitner = Leitner::INITIAL();

        $this->totalFailures++;
        $this->calculeAverage();

        $this->setNextDate(CarbonImmutable::today());

        return $this;
    }

    private function beRight(): self
    {
        $this->leitner = $this->leitner->next();
        $newDate = $this->leitner->getDateWithIncrement();

        $this->totalSuccess++;
        $this->calculeAverage();

        $this->setNextDate($newDate);

        return $this;
    }

    private function calculeAverage(): self
    {
        $total = $this->getTotal();
        if ($total <= 0) {
            $this->average = 0;
            return $this;
        }

        $difference = $this->totalSuccess - $this->totalFailures;
        $this->average = round($difference / $total, 2);
        return $this;
    }


}
