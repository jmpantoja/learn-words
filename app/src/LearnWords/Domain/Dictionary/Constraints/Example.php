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


use LearnWords\Domain\Dictionary;
use PlanB\Edge\Infrastructure\Symfony\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class Example extends Collection
{

    public function ignoreWhen($value): bool
    {
        return $value instanceof Dictionary\Example;
    }

    protected function getConstraints(): array
    {
        return [
            'sample' => [
                new NotBlank(),
                new Length([
                    'min' => 4
                ])
            ],
            'translation' => [
                new NotBlank(),
                new Length([
                    'min' => 4
                ])
            ]
        ];
    }
}
