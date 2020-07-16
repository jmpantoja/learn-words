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

namespace LearnWords\Term\Infrastructure\Symfony\Form\Type;


use LearnWords\Term\Domain\Model\Lang;
use PlanB\Edge\Infrastructure\Symfony\Form\Type\EnumType;
use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderFactory;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

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
