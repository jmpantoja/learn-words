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

namespace PlanB\Edge\Infrastructure\Symfony\Form;


use PlanB\Edge\Infrastructure\Symfony\Validator\CompoundBuilder;
use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderFactory;

interface CompoundToObjectMapperInterface
{
    public function mapDataToObject(array $data): object;

    public function buildConstraints(CompoundBuilder $builder, array $options): void;

}
