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

namespace LearnWords\Domain\Dictionary\EntryPersistence;


use LearnWords\Domain\Dictionary\Entry;
use PlanB\Edge\Domain\Event\DomainEvent;

final class EntryHasBeenImported extends DomainEvent
{
    private Entry $entry;

    public function __construct(Entry $entry)
    {
        $this->entry = $entry;
        parent::__construct();
    }
    
    public function getEntry(): Entry
    {
        return $this->entry;
    }

}
