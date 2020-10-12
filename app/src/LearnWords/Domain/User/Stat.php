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

final class Stat
{
    private StatId $id;
    private User $user;

    private CarbonImmutable $date;

    private int $today = 0;
    private int $initial = 0;
    private int $eachDay = 0;
    private int $eachThreeDays = 0;
    private int $eachWeek = 0;
    private int $eachTwoWeeks = 0;
    private int $eachMonth = 0;

    public function __construct(User $user, LeitnerStatus $status)
    {
        $this->id = new StatId();
        $this->user = $user;
        $this->date = CarbonImmutable::today();

        $this->update($status);
    }

    public function update(LeitnerStatus $status): self
    {
        $this->today = $status->getToday();
        $this->initial = $status->getInitial();
        $this->eachDay = $status->getEachDay();
        $this->eachThreeDays = $status->getEachThreeDays();
        $this->eachWeek = $status->getEachWeek();
        $this->eachTwoWeeks = $status->getEachTwoWeeks();
        $this->eachMonth = $status->getEachMonth();
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getDate(): CarbonImmutable
    {
        return $this->date;
    }

    public function toArray(): array
    {
        return [
            'date' => $this->date->format('d-M'),
            'eachDay' => $this->today + $this->initial + $this->eachDay,
            'eachThreeDays' => $this->eachThreeDays,
            'eachWeek' => $this->eachWeek,
            'eachTwoWeeks' => $this->eachTwoWeeks,
            'eachMonth' => $this->eachMonth,
        ];
    }

}


