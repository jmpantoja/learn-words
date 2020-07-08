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
use LearnWords\Term\Domain\Model\Word;
use PlanB\Edge\Application\UseCase\SaveCommand;

final class SaveTerm extends SaveCommand
{
    public ?Word $word = null;

    protected function build(Term $term = null): Term
    {
        if (is_null($term)) {
            $term = $this->create();
        }

        return $this->update($term);
    }

    private function create(): Term
    {
        return new Term(...[
            new TermId(),
            $this->word
        ]);
    }

    private function update(Term $term): Term
    {
        return $term->setWord($this->word);
    }


}
