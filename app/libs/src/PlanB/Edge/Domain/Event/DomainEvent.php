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

namespace PlanB\Edge\Domain\Event;


use Carbon\CarbonImmutable;
use DateTimeInterface;

abstract class DomainEvent implements DomainEventInterface
{
    private DateTimeInterface $when;

    protected function __construct(DateTimeInterface $when = null)
    {
        $this->when = $when ?? CarbonImmutable::now();
    }

    /**
     * @return DateTimeInterface
     */
    public function when(): DateTimeInterface
    {
        return $this->when;
    }
}
