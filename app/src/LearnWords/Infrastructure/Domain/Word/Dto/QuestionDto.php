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

namespace LearnWords\Infrastructure\Domain\Word\Dto;

use PlanB\Edge\Domain\Entity\Dto;

final class QuestionDto extends Dto
{
    public ?string $wording = null;
    public ?string $description = null;

    public function update($entity): object
    {
        // TODO: Implement update() method.
    }

    public function create(): object
    {
        // TODO: Implement create() method.
    }
}
