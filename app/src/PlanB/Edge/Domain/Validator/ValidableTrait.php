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

use PlanB\Edge\Domain\Validator\Exception\ValidationException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\ValidatorBuilder;

trait ValidableTrait
{
    /**
     * @param mixed $input
     */
    public function ensure($input): void
    {
        $violations = $this->validate($input);

        if ($violations->count() > 0) {
            throw new ValidationException($violations);
        }
    }

    /**
     * @param mixed $input
     * @return ConstraintViolationListInterface
     */
    public static function validate($input): ConstraintViolationListInterface
    {
        $validator = (new ValidatorBuilder())->getValidator();
        $constraints = static::getConstraints();

        return $validator->validate($input, $constraints);
    }

    public static function isValid($input): bool
    {
        return static::validate($input)->count() === 0;

    }

    abstract public static function getConstraints();
}
