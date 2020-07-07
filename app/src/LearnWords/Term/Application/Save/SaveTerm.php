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
use LearnWords\Term\Domain\Model\Word;
use PlanB\Edge\Application\UseCase\PersistenceCommand;
use PlanB\Edge\Domain\Entity\EntityId;
use PlanB\Edge\Domain\Entity\EntityInterface;

final class SaveTerm extends PersistenceCommand
{
    public ?EntityId $id = null;
    public ?Word $word = null;


    protected function newInstance(): Term
    {
        return new Term($this->word);
    }
}
