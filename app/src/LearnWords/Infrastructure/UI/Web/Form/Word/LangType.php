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

namespace LearnWords\Infrastructure\UI\Web\Form\Word;


use LearnWords\Domain\Word\Lang;
use PlanB\Edge\Domain\Enum\Enum;
use PlanB\Edge\Infrastructure\Symfony\Form\FormSerializer;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\EnumType;
use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderFactory;

final class LangType extends EnumType
{

    /**
     * @return string
     */
    public function getClass(): string
    {
        return Lang::class;
    }

}
