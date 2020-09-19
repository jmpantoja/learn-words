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
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @author Maxime Steinhausser <maxime.steinhausser@gmail.com>
 */
class CompositeValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Composite) {
            throw new UnexpectedTypeException($constraint, Composite::class);
        }

        $context = $this->context;
        $validator = $context->getValidator()->inContext($context);

        $validator->validate($value, $constraint->constraints);
    }
}
