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

namespace PlanB\Edge\Domain\Validator;


use Symfony\Component\Validator\ConstraintViolationListInterface;

interface Validable
{
    public function ensureIsValid($data): void;

    public static function validate($input): ConstraintViolationListInterface;

    public static function isValid($data): bool;

    public static function getConstraints(): array;

    public static function configureValidator(ConstraintsFactory $factory): void;

}
