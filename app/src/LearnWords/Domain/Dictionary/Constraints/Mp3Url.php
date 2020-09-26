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
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Url;

final class Mp3Url extends Composite
{
    protected function getConstraints(array $options): array
    {
        return [
            new Url(),
            new Regex([
                'pattern' => '/\.mp3$/'
            ])
        ];
    }
}
