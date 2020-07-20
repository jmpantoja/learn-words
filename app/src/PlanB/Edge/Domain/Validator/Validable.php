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
    /**
     * @param mixed $data
     */
    public function ensureIsValid($data): void;

    /**
     * @param mixed $input
     * @return ConstraintViolationListInterface
     */
    public static function validate($input): ConstraintViolationListInterface;

    /**
     * @param mixed $data
     * @return bool
     */
    public static function isValid($data): bool;

    public static function getConstraints(): array;

    public static function configureValidator(ConstraintsFactory $factory): void;

}
