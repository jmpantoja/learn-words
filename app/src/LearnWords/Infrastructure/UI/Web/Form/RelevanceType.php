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

namespace LearnWords\Infrastructure\UI\Web\Form;

use LearnWords\Domain\Dictionary\Relevance;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\SingleType;

final class RelevanceType extends SingleType
{
    public function getConstraints()
    {
        return Relevance::getConstraints();
    }

    public function transform(?object $relevance)
    {
        if ($relevance instanceof Relevance) {
            return $relevance->getRelevance();
        }

        return null;
    }

    public function reverse($data): Relevance
    {
        return new Relevance((int)$data);
    }
}
