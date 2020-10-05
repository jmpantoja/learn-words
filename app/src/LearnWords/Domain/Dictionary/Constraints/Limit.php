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

namespace LearnWords\Domain\Dictionary\Constraints;


use PlanB\Edge\Infrastructure\Symfony\Constraints\Composite;
use Symfony\Component\Validator\Constraints\Range;

final class Limit extends Composite
{
    /**
     * @inheritDoc
     */
    protected function getConstraints(array $options): array
    {
        return [
            new Range([
                'min' => 10,
                'max' => 1000
            ])
        ];
    }
}
