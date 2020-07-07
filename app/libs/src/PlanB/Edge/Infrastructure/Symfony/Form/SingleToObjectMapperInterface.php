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


use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderFactory;
use PlanB\Edge\Infrastructure\Symfony\Validator\ConstraintBuilderInterface;
use PlanB\Edge\Infrastructure\Symfony\Validator\SingleBuilder;
use Symfony\Component\Validator\Constraints\Collection;

interface SingleToObjectMapperInterface
{
    public function mapValueToObject($data): object;

    public function buildConstraints(SingleBuilder $builder, array $options): void;
}
