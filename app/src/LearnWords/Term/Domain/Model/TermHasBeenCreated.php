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

namespace LearnWords\Term\Domain\Model;


use Carbon\CarbonImmutable;
use DateTimeInterface;
use PlanB\Edge\Domain\Event\DomainEvent;

final class TermHasBeenCreated implements DomainEvent
{
    /**
     * @var TermId
     */
    private TermId $termId;

    public function __construct(TermId $termId)
    {
        $this->termId = $termId;
        $this->occurredAt = CarbonImmutable::now();
    }

    /**
     * @return DateTimeInterface
     */
    public function occurredAt(): DateTimeInterface
    {
        return $this->occurredAt;
    }

    /**
     * @return TermId
     */
    public function termId(): TermId
    {
        return $this->termId;
    }
}
