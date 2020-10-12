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

use LearnWords\Domain\Dictionary\Lang;
use PlanB\Edge\Infrastructure\Symfony\Constraints\Collection;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class Word extends Collection
{
    protected function getConstraints(): array
    {
        return [
            'word' => [
                new NotBlank(),
                new Length([
                    'min' => 2
                ])
            ],
            'lang' => [
                new Type([
                    'type' => Lang::class
                ])
            ]
        ];
    }

    public function ignoreWhen($value): bool
    {
        return $value instanceof \LearnWords\Domain\Dictionary\Word;
    }
}
