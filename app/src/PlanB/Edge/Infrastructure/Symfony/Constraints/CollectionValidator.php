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

namespace PlanB\Edge\Infrastructure\Symfony\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\CollectionValidator as SymfonyCollectionValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

final class CollectionValidator extends SymfonyCollectionValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Collection) {
            throw new UnexpectedTypeException($constraint, Collection::class);
        }

        if ($constraint->ignoreWhen($value)) {
            return;
        }

        parent::validate($value, $constraint);
    }

}
