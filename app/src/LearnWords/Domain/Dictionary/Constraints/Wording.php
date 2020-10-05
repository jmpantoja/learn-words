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


use PlanB\Edge\Infrastructure\Symfony\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use LearnWords\Domain\Dictionary;

final class Wording extends Collection
{

    public function ignoreWhen($value): bool
    {
        return $value instanceof Dictionary\Wording;
    }

    protected function getConstraints(): array
    {
        return [
            'wording' => [
                new NotBlank(),
                new Length([
                    'min' => 2
                ])
            ],
            'description' => [
//                new NotBlank(),
                new Length([
                    'min' => 2
                ])
            ]
        ];
    }
}
