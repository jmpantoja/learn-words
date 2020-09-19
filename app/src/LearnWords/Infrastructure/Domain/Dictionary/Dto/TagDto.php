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

namespace LearnWords\Infrastructure\Domain\Dictionary\Dto;


use LearnWords\Domain\Dictionary\Word;
use PlanB\Edge\Domain\Dto\Dto;

final class TagDto extends Dto
{
    public $tag;
}
