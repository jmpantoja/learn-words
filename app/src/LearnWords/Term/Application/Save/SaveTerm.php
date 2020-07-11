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

namespace LearnWords\Term\Application\Save;

use LearnWords\Term\Domain\Model\Term;
use LearnWords\Term\Domain\Model\TermId;
use PlanB\Edge\Application\UseCase\SaveCommand;
use PlanB\Edge\Shared\Exception\InvalidTypeException;

final class SaveTerm extends SaveCommand
{
    protected function create(array $data): Term
    {
        return new Term(...[
            new TermId(),
            $data['word']
        ]);
    }

    protected function update(array $data, Term $term = null): Term
    {
        $term = $this->ensureEntityType($term);

        return $term->update(...[
            $data['word']
        ]);
    }

    protected function ensureEntityType(?Term $term): Term
    {
        if (null === $term) {
            throw new InvalidTypeException($term, Term::class);
        }
        return $term;
    }
}
