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

namespace LearnWords\Domain\Dictionary\TagPersistence;

use LearnWords\Domain\Dictionary\Entry;
use LearnWords\Domain\Dictionary\Tag;
use PlanB\Edge\Domain\Event\DomainEvent;

final class TagHasBeenCreated extends DomainEvent
{
    private Tag $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
        parent::__construct();
    }

    /**
     * @return Entry
     */
    public function getTag(): Tag
    {
        return $this->tag;
    }

}
