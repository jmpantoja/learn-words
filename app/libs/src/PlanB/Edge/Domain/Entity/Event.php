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

namespace PlanB\Edge\Domain\Entity;


use DateTimeInterface;

final class Event
{
    private int $id;

    private string $name;

    private string $event;

    private DateTimeInterface $date;

    public function __construct(string $name, string $event, DateTimeInterface $date)
    {
        $this->name = $name;
        $this->event = $event;
        $this->date = $date;
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function event(): string
    {
        return $this->event;
    }

    /**
     * @return DateTimeInterface
     */
    public function date(): DateTimeInterface
    {
        return $this->date;
    }


}
