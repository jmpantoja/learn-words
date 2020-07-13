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
use Symfony\Component\Validator\ConstraintViolationListInterface;

interface SingleToObjectMapperInterface
{
    public function mapValueToObject($data): object;

    public function validate($data): ConstraintViolationListInterface;
}