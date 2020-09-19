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


use LearnWords\Domain\Dictionary\Lang;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\EnumType;

final class LangType extends EnumType
{
    public function getDataClass(): string
    {
        return Lang::class;
    }
}
