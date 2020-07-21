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

namespace PlanB\Edge\Domain\Validator\Traits;

use PlanB\Edge\Domain\Validator\ConstraintsFactory;
use PlanB\Edge\Domain\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\ValidatorBuilder;

trait ValidableTrait
{
    /**
     * @param mixed $data
     * @throws ValidationFailedException
     */
    public function ensureIsValid($data): void
    {
        $violations = static::validate($data);

        if (0 === $violations->count()) {
            return;
        }

        throw new ValidationFailedException($violations);
    }

    /**
     * @param mixed $data
     * @return bool
     */
    public static function isValid($data): bool
    {
        $violations = static::validate($data);
        return 0 === $violations->count();
    }

    /**
     * @param mixed $input
     * @return ConstraintViolationListInterface
     */
    public static function validate($input): ConstraintViolationListInterface
    {
        $validator = (new ValidatorBuilder())->getValidator();
        $constraints = self::getConstraints();

        return $validator->validate($input, $constraints);
    }

    public static function getConstraints(): array
    {
        $factory = new ConstraintsFactory();
        static::configureValidator($factory);
        return $factory->getConstraints();
    }

    abstract public static function configureValidator(ConstraintsFactory $factory): void;
}
