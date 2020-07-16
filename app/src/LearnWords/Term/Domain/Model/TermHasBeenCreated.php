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


use PlanB\Edge\Domain\Event\DomainEvent;

final class TermHasBeenCreated extends DomainEvent
{

    private Term $term;

    public function __construct(Term $term)
    {
        $this->term = $term;
        parent::__construct();
    }

    /**
     * @return Term
     */
    public function getTerm(): Term
    {
        return $this->term;
    }

}
