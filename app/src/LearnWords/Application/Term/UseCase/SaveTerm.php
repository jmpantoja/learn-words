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

namespace LearnWords\Application\Term\UseCase;

use LearnWords\Domain\Term\Term;
use PlanB\Edge\Application\UseCase\SaveCommand;

final class SaveTerm
{
    private Term $term;

    /**
     * SaveTerm constructor.
     * @param Term $term
     */
    public function __construct(Term $term)
    {
        $this->term = $term;
    }

    /**
     * @return Term
     */
    public function getTerm(): Term
    {
        return $this->term;
    }


}
