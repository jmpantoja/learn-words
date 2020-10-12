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


final class LeitnerStatus
{
    private int $today = 0;
    private int $initial = 0;
    private int $eachDay = 0;
    private int $eachThreeDays = 0;
    private int $eachWeek = 0;
    private int $eachTwoWeeks = 0;
    private int $eachMonth = 0;


    static public function make()
    {
        return new self();
    }

    public function addLeitnerTotal(Leitner $leitner, int $total)
    {
        switch ($leitner->getKey()) {
            case (string)Leitner::TODAY():
                $this->today = $total;
                break;
            case (string)Leitner::INITIAL():
                $this->initial = $total;
                break;
            case (string)Leitner::EACH_DAY():
                $this->eachDay = $total;
                break;
            case (string)Leitner::EACH_THREE_DAYS():
                $this->eachThreeDays = $total;
                break;
            case (string)Leitner::EACH_WEEK():
                $this->eachWeek = $total;
                break;
            case (string)Leitner::EACH_TWO_WEEKS():
                $this->eachTwoWeeks = $total;
                break;
            case (string)Leitner::EACH_MONTH():
                $this->eachMonth = $total;
                break;
        }
    }

    /**
     * @return int
     */
    public function getToday(): int
    {
        return $this->today;
    }

    /**
     * @return int
     */
    public function getInitial(): int
    {
        return $this->initial;
    }

    /**
     * @return int
     */
    public function getEachDay(): int
    {
        return $this->eachDay;
    }

    /**
     * @return int
     */
    public function getEachThreeDays(): int
    {
        return $this->eachThreeDays;
    }

    /**
     * @return int
     */
    public function getEachWeek(): int
    {
        return $this->eachWeek;
    }

    /**
     * @return int
     */
    public function getEachTwoWeeks(): int
    {
        return $this->eachTwoWeeks;
    }

    /**
     * @return int
     */
    public function getEachMonth(): int
    {
        return $this->eachMonth;
    }
}
